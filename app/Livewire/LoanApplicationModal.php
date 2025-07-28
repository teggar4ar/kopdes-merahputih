<?php

namespace App\Livewire;

use App\Models\Loan;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class LoanApplicationModal extends Component
{
    public $showModal = false;
    public $showConfirmation = false;
    public $loanAmount = '';
    public $loanTerm = '';
    public $loanPurpose = '';
    public $isProcessing = false;

    // Calculator properties
    public $interestRate = 12; // Default 12% annually
    public $monthlyPayment = 0;
    public $totalInterest = 0;
    public $totalPayment = 0;

    protected $listeners = ['openLoanModal'];

    protected $rules = [
        'loanAmount' => 'required|numeric|min:100000|max:50000000', // Min 100k, Max 50M
        'loanTerm' => 'required|in:3,6,12',
        'loanPurpose' => 'required|string|min:10|max:500',
    ];

    protected $messages = [
        'loanAmount.required' => 'Jumlah pinjaman harus diisi.',
        'loanAmount.numeric' => 'Jumlah pinjaman harus berupa angka.',
        'loanAmount.min' => 'Jumlah pinjaman minimal Rp 100.000.',
        'loanAmount.max' => 'Jumlah pinjaman maksimal Rp 50.000.000.',
        'loanTerm.required' => 'Jangka waktu harus dipilih.',
        'loanTerm.in' => 'Jangka waktu harus 3, 6, atau 12 bulan.',
        'loanPurpose.required' => 'Tujuan pinjaman harus diisi.',
        'loanPurpose.min' => 'Tujuan pinjaman minimal 10 karakter.',
        'loanPurpose.max' => 'Tujuan pinjaman maksimal 500 karakter.',
    ];

    public function mount()
    {
        // Get interest rate from system settings
        $this->interestRate = SystemSetting::getValue('loan_interest_rate', 12);
    }

    public function updated($propertyName)
    {
        // Clean loanAmount input before validation
        if ($propertyName === 'loanAmount') {
            $this->loanAmount = $this->cleanNumericInput($this->loanAmount);
        }

        $this->validateOnly($propertyName);

        // Update calculations when loan amount or term changes
        if (in_array($propertyName, ['loanAmount', 'loanTerm'])) {
            $this->calculateLoan();
        }
    }

    /**
     * Clean numeric input by removing formatting (commas, spaces, etc.)
     */
    private function cleanNumericInput($value)
    {
        if (empty($value)) {
            return '';
        }

        // Remove all non-numeric characters except decimal point
        return preg_replace('/[^0-9.]/', '', $value);
    }

    /**
     * Override data preparation for validation to clean formatted inputs
     */
    protected function getPropertyDataToValidate($property)
    {
        $data = parent::getPropertyDataToValidate($property);

        // Clean loanAmount before validation
        if ($property === 'loanAmount' && isset($data[$property])) {
            $data[$property] = $this->cleanNumericInput($data[$property]);
        }

        return $data;
    }

    public function calculateLoan()
    {
        if ($this->loanAmount && $this->loanTerm && is_numeric($this->loanAmount) && is_numeric($this->loanTerm)) {
            $principal = (float) $this->loanAmount;
            $termMonths = (int) $this->loanTerm;
            $annualRate = (float) $this->interestRate / 100;
            $monthlyRate = $annualRate / 12;

            // Calculate monthly payment using PMT formula
            if ($monthlyRate > 0) {
                $this->monthlyPayment = $principal * ($monthlyRate * pow(1 + $monthlyRate, $termMonths)) / (pow(1 + $monthlyRate, $termMonths) - 1);
            } else {
                $this->monthlyPayment = $principal / $termMonths;
            }

            $this->totalPayment = $this->monthlyPayment * $termMonths;
            $this->totalInterest = $this->totalPayment - $principal;
        } else {
            $this->resetCalculations();
        }
    }

    public function resetCalculations()
    {
        $this->monthlyPayment = 0;
        $this->totalInterest = 0;
        $this->totalPayment = 0;
    }

    public function openLoanModal()
    {
        // Check if user has pending or active loan
        $existingLoan = Auth::user()->loans()
            ->whereIn('status', ['pending', 'approved', 'disbursed'])
            ->first();

        if ($existingLoan) {
            session()->flash('error', 'Anda sudah memiliki pengajuan atau pinjaman aktif.');
            return;
        }

        $this->showModal = true;
        $this->reset(['loanAmount', 'loanTerm', 'loanPurpose', 'showConfirmation']);
        $this->resetCalculations();
        $this->resetValidation();
    }

    public function submit()
    {
        $this->validate();

        $this->showConfirmation = true;
    }

    public function confirmSubmission()
    {
        // Debug: Add some logging to see if this method is being called
        Log::info('confirmSubmission called', [
            'user_id' => Auth::id(),
            'loanAmount' => $this->loanAmount,
            'loanTerm' => $this->loanTerm,
            'loanPurpose' => $this->loanPurpose
        ]);

        // First, ensure all data is valid
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed in confirmSubmission', [
                'errors' => $e->validator->errors()->toArray()
            ]);
            session()->flash('error', 'Data tidak valid: ' . implode(', ', $e->validator->errors()->all()));
            return;
        }

        $this->isProcessing = true;

        try {
            // Check if user already has an active loan
            $existingLoan = Loan::where('user_id', Auth::id())
                ->whereIn('status', ['pending', 'approved', 'active'])
                ->first();

            if ($existingLoan) {
                session()->flash('error', 'Anda sudah memiliki pinjaman yang sedang berjalan atau menunggu persetujuan.');
                $this->isProcessing = false;
                return;
            }

            // Ensure calculations are up to date
            $this->calculateLoan();

            // Create loan application
            $loan = Loan::create([
                'user_id' => Auth::id(),
                'principal_amount' => (float) $this->cleanNumericInput($this->loanAmount),
                'interest_rate' => (float) $this->interestRate,
                'duration_months' => (int) $this->loanTerm,
                'monthly_installment' => (float) $this->monthlyPayment,
                'reason' => $this->loanPurpose,
                'status' => 'pending',
                'application_date' => now(),
            ]);

            Log::info('Loan created successfully', ['loan_id' => $loan->id]);

            if ($loan) {
                session()->flash('success', 'Pengajuan pinjaman berhasil disubmit. Mohon menunggu persetujuan admin.');
                $this->closeModal();
                // Refresh the parent component
                $this->dispatch('loan-submitted');
            } else {
                session()->flash('error', 'Gagal menyimpan pengajuan pinjaman. Silakan coba lagi.');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in confirmSubmission', [
                'errors' => $e->validator->errors()->toArray()
            ]);
            session()->flash('error', 'Data tidak valid: ' . implode(', ', $e->validator->errors()->all()));
        } catch (\Exception $e) {
            Log::error('Exception in confirmSubmission', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        } finally {
            $this->isProcessing = false;
        }
    }

    public function cancelConfirmation()
    {
        $this->showConfirmation = false;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->showConfirmation = false;
        $this->reset(['loanAmount', 'loanTerm', 'loanPurpose']);
        $this->resetCalculations();
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.loan-application-modal');
    }
}
