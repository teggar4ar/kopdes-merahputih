<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property float $loan_amount
 * @property float $interest_rate
 * @property int $loan_term_months
 * @property float $monthly_installment
 * @property string $loan_purpose
 * @property string $status
 * @property \Illuminate\Support\Carbon $application_date
 * @property \Illuminate\Support\Carbon|null $approval_date
 * @property int|null $approved_by_admin_id
 * @property string|null $approval_notes
 * @property \Illuminate\Support\Carbon|null $first_installment_date
 * @property float $total_paid
 * @property float $remaining_balance
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Loan extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'loan_amount',
        'interest_rate',
        'loan_term_months',
        'monthly_installment',
        'loan_purpose',
        'status',
        'application_date',
        'approval_date',
        'approved_by_admin_id',
        'approval_notes',
        'first_installment_date',
        'total_paid',
        'remaining_balance',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'application_date' => 'datetime',
            'approval_date' => 'datetime',
            'first_installment_date' => 'datetime',
            'loan_amount' => 'decimal:2',
            'interest_rate' => 'decimal:2',
            'monthly_installment' => 'decimal:2',
            'total_paid' => 'decimal:2',
            'remaining_balance' => 'decimal:2',
        ];
    }

    // ===============================
    // RELATIONSHIPS
    // ===============================

    /**
     * Get the user that owns this loan.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin that approved this loan.
     */
    public function approvedByAdmin()
    {
        return $this->belongsTo(User::class, 'approved_by_admin_id');
    }

    /**
     * Get the loan installments for this loan.
     */
    public function loanInstallments()
    {
        return $this->hasMany(LoanInstallment::class);
    }

    // ===============================
    // SCOPES
    // ===============================

    /**
     * Scope a query to only include pending loans.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include approved loans.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include active loans.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include completed loans.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include rejected loans.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // ===============================
    // BUSINESS LOGIC METHODS
    // ===============================

    /**
     * Check if loan is pending approval.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if loan is approved but not yet active.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if loan is currently active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if loan is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if loan is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Calculate total amount to be paid (principal + interest).
     */
    public function calculateTotalAmount(): float
    {
        return $this->monthly_installment * $this->loan_term_months;
    }

    /**
     * Calculate total interest amount.
     */
    public function calculateTotalInterest(): float
    {
        return $this->calculateTotalAmount() - $this->loan_amount;
    }

    /**
     * Calculate progress percentage.
     */
    public function calculateProgress(): float
    {
        $totalAmount = $this->calculateTotalAmount();
        if ($totalAmount == 0) {
            return 0;
        }
        return ($this->total_paid / $totalAmount) * 100;
    }

    /**
     * Get formatted loan amount for display.
     */
    public function getFormattedLoanAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->loan_amount, 0, ',', '.');
    }

    /**
     * Get formatted monthly installment for display.
     */
    public function getFormattedMonthlyInstallmentAttribute(): string
    {
        return 'Rp ' . number_format($this->monthly_installment, 0, ',', '.');
    }

    /**
     * Get formatted remaining balance for display.
     */
    public function getFormattedRemainingBalanceAttribute(): string
    {
        return 'Rp ' . number_format($this->remaining_balance, 0, ',', '.');
    }

    /**
     * Get status in Indonesian.
     */
    public function getStatusIndonesianAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'active' => 'Aktif',
            'completed' => 'Selesai',
            'rejected' => 'Ditolak',
            default => ucfirst($this->status),
        };
    }

    /**
     * Approve the loan.
     */
    public function approve(int $adminId, string $notes = null): void
    {
        $this->update([
            'status' => 'approved',
            'approval_date' => now(),
            'approved_by_admin_id' => $adminId,
            'approval_notes' => $notes,
        ]);
    }

    /**
     * Reject the loan.
     */
    public function reject(int $adminId, string $notes = null): void
    {
        $this->update([
            'status' => 'rejected',
            'approved_by_admin_id' => $adminId,
            'approval_notes' => $notes,
        ]);
    }

    /**
     * Activate the loan (when first installment starts).
     */
    public function activate(): void
    {
        $this->update([
            'status' => 'active',
            'remaining_balance' => $this->calculateTotalAmount(),
        ]);
    }

    /**
     * Mark loan as completed.
     */
    public function complete(): void
    {
        $this->update([
            'status' => 'completed',
            'remaining_balance' => 0,
        ]);
    }

    /**
     * Process installment payment.
     */
    public function processPayment(float $amount): void
    {
        $this->increment('total_paid', $amount);
        $this->decrement('remaining_balance', $amount);

        // Mark as completed if fully paid
        if ($this->remaining_balance <= 0) {
            $this->complete();
        }
    }
}
