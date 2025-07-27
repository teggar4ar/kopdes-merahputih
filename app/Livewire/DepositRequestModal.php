<?php

namespace App\Livewire;

use App\Models\SavingsTransaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class DepositRequestModal extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $showConfirmation = false;
    public $savingsType = '';
    public $paymentMonth = '';
    public $amount = '';
    public $proofOfTransfer = null;
    public $notes = '';
    public $isProcessing = false;

    protected $listeners = ['openDepositModal'];

    protected $rules = [
        'savingsType' => 'required|in:pokok,wajib,sukarela',
        'paymentMonth' => 'required_if:savingsType,wajib|nullable|string',
        'amount' => 'required|numeric|min:50000',
        'proofOfTransfer' => 'required|image|max:2048', // Max 2MB
        'notes' => 'nullable|string|max:255'
    ];

    protected $messages = [
        'savingsType.required' => 'Jenis simpanan harus dipilih.',
        'savingsType.in' => 'Jenis simpanan tidak valid.',
        'paymentMonth.required_if' => 'Bulan pembayaran harus dipilih untuk simpanan wajib.',
        'amount.required' => 'Jumlah setoran harus diisi.',
        'amount.numeric' => 'Jumlah setoran harus berupa angka.',
        'amount.min' => 'Jumlah setoran minimal Rp 50.000.',
        'proofOfTransfer.required' => 'Bukti transfer harus diupload.',
        'proofOfTransfer.image' => 'Bukti transfer harus berupa gambar (JPG, PNG).',
        'proofOfTransfer.max' => 'Ukuran file maksimal 2MB.',
        'notes.max' => 'Catatan maksimal 255 karakter.'
    ];

    public function openDepositModal()
    {
        $this->showModal = true;
        $this->showConfirmation = false;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->showConfirmation = false;
        $this->savingsType = '';
        $this->paymentMonth = '';
        $this->amount = '';
        $this->proofOfTransfer = null;
        $this->notes = '';
        $this->isProcessing = false;
        $this->resetErrorBag();
    }

    public function showConfirmationStep()
    {
        // Validate the form first
        $this->validate();

        // Additional validation based on savings type
        $validationResult = $this->validateDepositRequest();
        if (!$validationResult['valid']) {
            $this->addError('amount', $validationResult['message']);
            return;
        }

        $this->showConfirmation = true;
    }

    public function backToForm()
    {
        $this->showConfirmation = false;
    }

    public function submitDeposit()
    {
        $this->isProcessing = true;

        try {
            // Final validation
            $this->validate();

            $validationResult = $this->validateDepositRequest();
            if (!$validationResult['valid']) {
                $this->addError('amount', $validationResult['message']);
                $this->isProcessing = false;
                return;
            }

            // Store the proof of transfer image
            $proofPath = $this->proofOfTransfer->store('deposit-proofs', 'public');

            // Determine transaction date based on savings type
            $transactionDate = now();
            if ($this->savingsType === 'wajib' && !empty($this->paymentMonth)) {
                // For mandatory savings, use the selected payment month
                $transactionDate = \Carbon\Carbon::createFromFormat('Y-m', $this->paymentMonth)->startOfMonth();
            }

            // Create deposit transaction
            SavingsTransaction::create([
                'user_id' => Auth::id(),
                'savings_type' => $this->savingsType,
                'transaction_type' => 'setor',
                'amount' => $this->amount,
                'transaction_date' => $transactionDate,
                'description' => $this->notes ?: $this->getDefaultDescription(),
                'transaction_proof_url' => $proofPath,
                'status' => 'pending' // Awaiting admin approval
            ]);

            // Prepare success message
            $successMessage = 'Permintaan setoran ' . $this->getSavingsTypeName() . ' sebesar Rp ' . number_format($this->amount, 0, ',', '.');

            if ($this->savingsType === 'wajib' && !empty($this->paymentMonth)) {
                $monthDate = \Carbon\Carbon::createFromFormat('Y-m', $this->paymentMonth);
                $successMessage .= ' untuk bulan ' . $monthDate->format('F Y');
            }

            $successMessage .= ' berhasil diajukan. Menunggu verifikasi admin.';

            // Show success message and close modal
            $this->dispatch('showAlert', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => $successMessage,
                'duration' => 5000
            ]);

            $this->closeModal();

            // Refresh the parent component
            $this->dispatch('refreshSavingsData');
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'Terjadi kesalahan saat memproses setoran. Silakan coba lagi.',
                'duration' => 5000
            ]);
        } finally {
            $this->isProcessing = false;
        }
    }

    private function validateDepositRequest()
    {
        /** @var User $user */
        $user = Auth::user();

        switch ($this->savingsType) {
            case 'pokok':
                // Check minimum amount for Pokok (must be exactly Rp 100,000 - one-time payment)
                if ($this->amount != 100000) {
                    return [
                        'valid' => false,
                        'message' => 'Simpanan Pokok harus tepat Rp 100.000.'
                    ];
                }

                // Check if already paid (one-time payment)
                $hasPokokPayment = $user->savingsTransactions()
                    ->where('savings_type', 'pokok')
                    ->where('transaction_type', 'setor')
                    ->whereIn('status', ['completed', 'pending'])
                    ->exists();

                if ($hasPokokPayment) {
                    return [
                        'valid' => false,
                        'message' => 'Simpanan Pokok sudah dibayar atau sedang dalam proses. Simpanan Pokok hanya dibayar sekali.'
                    ];
                }
                break;

            case 'wajib':
                // Check minimum amount for Wajib (must be exactly Rp 50,000 - monthly payment)
                if ($this->amount != 50000) {
                    return [
                        'valid' => false,
                        'message' => 'Simpanan Wajib harus tepat Rp 50.000.'
                    ];
                }

                // Validate payment month is selected
                if (empty($this->paymentMonth)) {
                    return [
                        'valid' => false,
                        'message' => 'Bulan pembayaran harus dipilih.'
                    ];
                }

                // Parse the selected month
                $paymentDate = \Carbon\Carbon::createFromFormat('Y-m', $this->paymentMonth)->startOfMonth();
                $memberSince = $user->created_at->startOfMonth();
                $currentMonth = now()->startOfMonth();
                $maxFutureMonth = $currentMonth->copy()->addMonths(3);

                // Check if payment month is valid (not before membership or more than 3 months ahead)
                if ($paymentDate < $memberSince || $paymentDate > $maxFutureMonth) {
                    return [
                        'valid' => false,
                        'message' => 'Bulan pembayaran tidak valid. Hanya bisa bayar sampai 3 bulan ke depan.'
                    ];
                }

                // Check if already paid for selected month
                $hasPaymentForMonth = $user->savingsTransactions()
                    ->where('savings_type', 'wajib')
                    ->where('transaction_type', 'setor')
                    ->where('amount', 50000)
                    ->whereIn('status', ['completed', 'pending'])
                    ->whereYear('transaction_date', $paymentDate->year)
                    ->whereMonth('transaction_date', $paymentDate->month)
                    ->exists();

                if ($hasPaymentForMonth) {
                    return [
                        'valid' => false,
                        'message' => 'Simpanan Wajib untuk bulan ' . $paymentDate->format('F Y') . ' sudah dibayar atau sedang dalam proses.'
                    ];
                }

                // Check sequential payment rule: must pay in order (no skipping months)
                // Get all paid months
                $paidMonths = $user->savingsTransactions()
                    ->where('savings_type', 'wajib')
                    ->where('transaction_type', 'setor')
                    ->where('amount', 50000)
                    ->whereIn('status', ['completed', 'pending'])
                    ->get()
                    ->map(function ($transaction) {
                        return $transaction->transaction_date->format('Y-m');
                    })
                    ->toArray();

                // Generate all months from membership to current payment month
                $allMonthsUntilPayment = [];
                $tempMonth = $memberSince->copy();
                while ($tempMonth < $paymentDate) {
                    $allMonthsUntilPayment[] = $tempMonth->format('Y-m');
                    $tempMonth->addMonth();
                }

                // Check if all previous months are paid
                foreach ($allMonthsUntilPayment as $requiredMonth) {
                    if (!in_array($requiredMonth, $paidMonths)) {
                        $requiredMonthDate = \Carbon\Carbon::createFromFormat('Y-m', $requiredMonth);
                        return [
                            'valid' => false,
                            'message' => 'Harus membayar simpanan wajib untuk bulan ' . $requiredMonthDate->format('F Y') . ' terlebih dahulu. Tidak boleh melompati bulan.'
                        ];
                    }
                }

                break;

            case 'sukarela':
                // Check minimum amount for Sukarela (min Rp 50,000)
                if ($this->amount < 50000) {
                    return [
                        'valid' => false,
                        'message' => 'Simpanan Sukarela minimal Rp 50.000.'
                    ];
                }
                break;
        }

        return ['valid' => true, 'message' => ''];
    }

    private function getDefaultDescription()
    {
        $savingsTypeName = $this->getSavingsTypeName();

        if ($this->savingsType === 'wajib' && !empty($this->paymentMonth)) {
            $monthDate = \Carbon\Carbon::createFromFormat('Y-m', $this->paymentMonth);
            return "Setoran {$savingsTypeName} untuk bulan " . $monthDate->format('F Y');
        }

        return "Setoran {$savingsTypeName}";
    }

    private function getSavingsTypeName()
    {
        return match ($this->savingsType) {
            'pokok' => 'Simpanan Pokok',
            'wajib' => 'Simpanan Wajib',
            'sukarela' => 'Simpanan Sukarela',
            default => 'Simpanan'
        };
    }

    public function render()
    {
        return view('livewire.deposit-request-modal');
    }
}
