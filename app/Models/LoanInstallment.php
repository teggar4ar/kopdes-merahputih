<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $loan_id
 * @property int $installment_number
 * @property \Illuminate\Support\Carbon $due_date
 * @property float $amount_due
 * @property float $amount_paid
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $payment_date
 * @property int|null $processed_by_admin_id
 * @property string|null $payment_notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class LoanInstallment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'loan_id',
        'installment_number',
        'due_date',
        'amount_due',
        'amount_paid',
        'status',
        'payment_date',
        'processed_by_admin_id',
        'payment_notes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'due_date' => 'datetime',
            'payment_date' => 'datetime',
            'amount_due' => 'decimal:2',
            'amount_paid' => 'decimal:2',
        ];
    }

    // ===============================
    // RELATIONSHIPS
    // ===============================

    /**
     * Get the loan that owns this installment.
     */
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    /**
     * Get the admin that processed this payment.
     */
    public function processedByAdmin()
    {
        return $this->belongsTo(User::class, 'processed_by_admin_id');
    }

    // ===============================
    // SCOPES
    // ===============================

    /**
     * Scope a query to only include pending installments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include paid installments.
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope a query to only include overdue installments.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    /**
     * Scope a query to only include partially paid installments.
     */
    public function scopePartiallyPaid($query)
    {
        return $query->where('status', 'partially_paid');
    }

    /**
     * Scope a query to filter by loan.
     */
    public function scopeForLoan($query, $loanId)
    {
        return $query->where('loan_id', $loanId);
    }

    // ===============================
    // BUSINESS LOGIC METHODS
    // ===============================

    /**
     * Check if installment is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if installment is paid.
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Check if installment is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->status === 'overdue';
    }

    /**
     * Check if installment is partially paid.
     */
    public function isPartiallyPaid(): bool
    {
        return $this->status === 'partially_paid';
    }

    /**
     * Calculate remaining amount to be paid.
     */
    public function getRemainingAmount(): float
    {
        return $this->amount_due - $this->amount_paid;
    }

    /**
     * Check if installment is due today or overdue.
     */
    public function isDueOrOverdue(): bool
    {
        return $this->due_date->isPast() || $this->due_date->isToday();
    }

    /**
     * Get formatted amount due for display.
     */
    public function getFormattedAmountDueAttribute(): string
    {
        return 'Rp ' . number_format($this->amount_due, 0, ',', '.');
    }

    /**
     * Get formatted amount paid for display.
     */
    public function getFormattedAmountPaidAttribute(): string
    {
        return 'Rp ' . number_format($this->amount_paid, 0, ',', '.');
    }

    /**
     * Get formatted remaining amount for display.
     */
    public function getFormattedRemainingAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->getRemainingAmount(), 0, ',', '.');
    }

    /**
     * Get status in Indonesian.
     */
    public function getStatusIndonesianAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Belum Dibayar',
            'paid' => 'Lunas',
            'overdue' => 'Terlambat',
            'partially_paid' => 'Dibayar Sebagian',
            default => ucfirst($this->status),
        };
    }

    /**
     * Process payment for this installment.
     */
    public function processPayment(float $amount, int $adminId, string $notes = null): void
    {
        $this->amount_paid += $amount;
        $this->payment_date = now();
        $this->processed_by_admin_id = $adminId;
        $this->payment_notes = $notes;

        // Update status based on payment
        if ($this->amount_paid >= $this->amount_due) {
            $this->status = 'paid';
        } else {
            $this->status = 'partially_paid';
        }

        $this->save();

        // Update loan's total paid and remaining balance
        $this->loan->processPayment($amount);
    }

    /**
     * Mark installment as overdue.
     */
    public function markAsOverdue(): void
    {
        if ($this->status === 'pending' && $this->due_date->isPast()) {
            $this->update(['status' => 'overdue']);
        }
    }
}
