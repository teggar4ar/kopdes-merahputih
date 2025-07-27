<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $transaction_type
 * @property string $savings_type
 * @property float $amount
 * @property string|null $description
 * @property string|null $transaction_proof_url
 * @property \Illuminate\Support\Carbon $transaction_date
 * @property int|null $processed_by_admin_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class SavingsTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'transaction_type',
        'savings_type',
        'amount',
        'description',
        'transaction_proof_url',
        'transaction_date',
        'processed_by_admin_id',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'transaction_date' => 'datetime',
            'amount' => 'decimal:2',
            'transaction_type' => 'string',
            'savings_type' => 'string',
            'status' => 'string',
        ];
    }

    // ===============================
    // RELATIONSHIPS
    // ===============================

    /**
     * Get the user that owns this savings transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin that processed this transaction.
     */
    public function processedByAdmin()
    {
        return $this->belongsTo(User::class, 'processed_by_admin_id');
    }

    // ===============================
    // SCOPES
    // ===============================

    /**
     * Scope a query to only include deposits.
     */
    public function scopeDeposits($query)
    {
        return $query->where('transaction_type', 'setor');
    }

    /**
     * Scope a query to only include withdrawals.
     */
    public function scopeWithdrawals($query)
    {
        return $query->where('transaction_type', 'tarik');
    }

    /**
     * Scope a query to filter by savings type.
     */
    public function scopeBySavingsType($query, $type)
    {
        return $query->where('savings_type', $type);
    }

    // ===============================
    // BUSINESS LOGIC METHODS
    // ===============================

    /**
     * Check if this is a deposit transaction.
     */
    public function isDeposit(): bool
    {
        return $this->transaction_type === 'setor';
    }

    /**
     * Check if this is a withdrawal transaction.
     */
    public function isWithdrawal(): bool
    {
        return $this->transaction_type === 'tarik';
    }

    /**
     * Get formatted amount for display.
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    /**
     * Get transaction type in Indonesian.
     */
    public function getTransactionTypeIndonesianAttribute(): string
    {
        return $this->transaction_type === 'setor' ? 'Setoran' : 'Penarikan';
    }

    /**
     * Get savings type in Indonesian.
     */
    public function getSavingsTypeIndonesianAttribute(): string
    {
        return match ($this->savings_type) {
            'pokok' => 'Simpanan Pokok',
            'wajib' => 'Simpanan Wajib',
            'sukarela' => 'Simpanan Sukarela',
            default => ucfirst($this->savings_type),
        };
    }

    /**
     * Get status in Indonesian.
     */
    public function getStatusIndonesianAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Persetujuan',
            'completed' => 'Selesai',
            'rejected' => 'Ditolak',
            default => ucfirst($this->status),
        };
    }

    // ===============================
    // QUERY SCOPES
    // ===============================

    /**
     * Scope to filter by transaction status.
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get only completed transactions.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope to get only pending transactions.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get only rejected transactions.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope to get pending withdrawal requests.
     */
    public function scopePendingWithdrawals($query)
    {
        return $query->where('transaction_type', 'tarik')
            ->where('status', 'pending');
    }
}
