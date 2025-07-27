<?php

namespace App\Livewire;

use App\Models\SavingsTransaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WithdrawalRequestModal extends Component
{
    public $showModal = false;
    public $showConfirmation = false;
    public $amount = '';
    public $notes = '';
    public $maxAmount = 0;
    public $isProcessing = false;

    protected $listeners = ['openWithdrawalModal'];

    protected $rules = [
        'amount' => 'required|numeric|min:50000',
        'notes' => 'nullable|string|max:255'
    ];

    protected $messages = [
        'amount.required' => 'Jumlah penarikan harus diisi.',
        'amount.numeric' => 'Jumlah penarikan harus berupa angka.',
        'amount.min' => 'Jumlah penarikan minimal Rp 50.000.',
        'notes.max' => 'Catatan maksimal 255 karakter.'
    ];

    public function mount()
    {
        $this->calculateMaxAmount();
    }

    public function openWithdrawalModal()
    {
        $this->calculateMaxAmount();

        // Check if user has pending withdrawal requests
        if ($this->hasPendingWithdrawal()) {
            $this->dispatch('showAlert', [
                'type' => 'warning',
                'title' => 'Penarikan Tertunda!',
                'message' => 'Anda memiliki permintaan penarikan yang sedang menunggu persetujuan admin. Harap tunggu hingga permintaan sebelumnya diproses.',
                'duration' => 6000
            ]);
            return;
        }

        // Check if user has completed mandatory savings obligations
        $savingsValidation = $this->validateSavingsObligations();
        if (!$savingsValidation['valid']) {
            $this->dispatch('showAlert', [
                'type' => 'warning',
                'title' => 'Kewajiban Simpanan Belum Terpenuhi!',
                'message' => $savingsValidation['message'],
                'duration' => 8000
            ]);
            return;
        }

        $this->showModal = true;
        $this->showConfirmation = false;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->showConfirmation = false;
        $this->amount = '';
        $this->notes = '';
        $this->isProcessing = false;
        $this->resetErrorBag();
    }

    public function showConfirmationStep()
    {
        $this->calculateMaxAmount();

        // Check if user has pending withdrawal requests
        if ($this->hasPendingWithdrawal()) {
            $this->dispatch('showAlert', [
                'type' => 'warning',
                'title' => 'Penarikan Tertunda!',
                'message' => 'Anda memiliki permintaan penarikan yang sedang menunggu persetujuan admin. Harap tunggu hingga permintaan sebelumnya diproses.',
                'duration' => 6000
            ]);
            $this->closeModal();
            return;
        }

        // Check if user has completed mandatory savings obligations
        $savingsValidation = $this->validateSavingsObligations();
        if (!$savingsValidation['valid']) {
            $this->dispatch('showAlert', [
                'type' => 'warning',
                'title' => 'Kewajiban Simpanan Belum Terpenuhi!',
                'message' => $savingsValidation['message'],
                'duration' => 8000
            ]);
            $this->closeModal();
            return;
        }

        // Add custom validation for max amount and minimum amount
        $this->rules['amount'] = "required|numeric|min:50000|max:{$this->maxAmount}";

        $validatedData = $this->validate();

        // Check if user has sufficient balance
        if ($validatedData['amount'] > $this->maxAmount) {
            $this->addError('amount', 'Jumlah penarikan melebihi saldo simpanan sukarela.');
            return;
        }

        // Check minimum withdrawal amount
        if ($validatedData['amount'] < 50000) {
            $this->addError('amount', 'Jumlah penarikan minimal Rp 50.000.');
            return;
        }

        $this->showConfirmation = true;
    }

    public function backToForm()
    {
        $this->showConfirmation = false;
    }

    public function submitWithdrawal()
    {
        $this->isProcessing = true;

        try {
            // Double-check for pending withdrawals before submitting
            if ($this->hasPendingWithdrawal()) {
                $this->dispatch('showAlert', [
                    'type' => 'warning',
                    'title' => 'Penarikan Tertunda!',
                    'message' => 'Anda memiliki permintaan penarikan yang sedang menunggu persetujuan admin.',
                    'duration' => 6000
                ]);
                $this->closeModal();
                return;
            }

            // Double-check savings obligations before submitting
            $savingsValidation = $this->validateSavingsObligations();
            if (!$savingsValidation['valid']) {
                $this->dispatch('showAlert', [
                    'type' => 'warning',
                    'title' => 'Kewajiban Simpanan Belum Terpenuhi!',
                    'message' => $savingsValidation['message'],
                    'duration' => 8000
                ]);
                $this->closeModal();
                return;
            }

            // Create withdrawal transaction
            SavingsTransaction::create([
                'user_id' => Auth::id(),
                'savings_type' => 'sukarela',
                'transaction_type' => 'tarik',
                'amount' => $this->amount,
                'transaction_date' => now(),
                'description' => $this->notes ?? 'Penarikan simpanan sukarela',
                'status' => 'pending' // Awaiting admin approval
            ]);

            // Show success message and close modal
            $this->dispatch('showAlert', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Permintaan penarikan sebesar Rp ' . number_format($this->amount, 0, ',', '.') . ' berhasil diajukan. Menunggu persetujuan admin.',
                'duration' => 5000
            ]);

            $this->closeModal();

            // Refresh the parent component
            $this->dispatch('refreshSavingsData');
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'Terjadi kesalahan saat memproses penarikan. Silakan coba lagi.',
                'duration' => 5000
            ]);
        } finally {
            $this->isProcessing = false;
        }
    }

    private function calculateMaxAmount()
    {
        /** @var User $user */
        $user = Auth::user();

        // Calculate available balance for sukarela savings (only completed transactions)
        $totalDeposits = $user->savingsTransactions()
            ->completed()
            ->where('savings_type', 'sukarela')
            ->where('transaction_type', 'setor')
            ->sum('amount');

        $totalWithdrawals = $user->savingsTransactions()
            ->completed()
            ->where('savings_type', 'sukarela')
            ->where('transaction_type', 'tarik')
            ->sum('amount');

        $this->maxAmount = $totalDeposits - $totalWithdrawals;
    }

    private function hasPendingWithdrawal()
    {
        /** @var User $user */
        $user = Auth::user();

        // Check if user has any pending withdrawal requests
        return $user->savingsTransactions()
            ->where('savings_type', 'sukarela')
            ->where('transaction_type', 'tarik')
            ->where('status', 'pending')
            ->exists();
    }

    private function validateSavingsObligations()
    {
        /** @var User $user */
        $user = Auth::user();

        // Check if user has paid one-time principal savings (Simpanan Pokok)
        if (!$user->hasPaidPrincipalSavings()) {
            return [
                'valid' => false,
                'message' => 'Anda harus membayar Simpanan Pokok (Rp 100.000) terlebih dahulu sebelum dapat melakukan penarikan.'
            ];
        }

        // Check if user is current on mandatory savings (monthly Rp 50,000)
        if (!$user->isCurrentOnMandatorySavings()) {
            $monthsSinceMembership = $user->created_at->diffInMonths(now());
            $expectedPayments = max(1, $monthsSinceMembership);
            $actualPayments = $user->getMandatoryPaymentsCount();
            $missingPayments = $expectedPayments - $actualPayments;

            return [
                'valid' => false,
                'message' => "Anda memiliki tunggakan Simpanan Wajib sebanyak {$missingPayments} bulan (Rp " . number_format($missingPayments * 50000, 0, ',', '.') . "). Harap melunasi terlebih dahulu sebelum melakukan penarikan."
            ];
        }

        // Check if user has paid current month's mandatory savings
        if (!$this->hasCurrentMonthMandatoryPayment()) {
            return [
                'valid' => false,
                'message' => 'Anda belum membayar Simpanan Wajib untuk bulan ini (Rp 50.000). Harap bayar terlebih dahulu sebelum melakukan penarikan.'
            ];
        }

        return ['valid' => true, 'message' => ''];
    }

    private function hasCurrentMonthMandatoryPayment()
    {
        /** @var User $user */
        $user = Auth::user();

        // Check if user has made mandatory payment this month
        return $user->savingsTransactions()
            ->where('savings_type', 'wajib')
            ->where('transaction_type', 'setor')
            ->where('amount', 50000)
            ->where('status', 'completed')
            ->whereYear('transaction_date', now()->year)
            ->whereMonth('transaction_date', now()->month)
            ->exists();
    }

    // Legacy method for backward compatibility
    private function hasCurrentMonthPrincipalPayment()
    {
        return $this->hasCurrentMonthMandatoryPayment();
    }

    public function render()
    {
        return view('livewire.withdrawal-request-modal');
    }
}
