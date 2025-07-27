<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $name
 * @property string $nik
 * @property string $email
 * @property string $phone_number
 * @property string $address
 * @property string|null $profile_picture_url
 * @property string|null $ktp_image_url
 * @property string $account_status
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @method bool hasRole(string|array $roles)
 * @method bool hasPermissionTo(string $permission)
 * @method void assignRole(string $role)
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nik',
        'email',
        'password',
        'phone_number',
        'address',
        'profile_picture_url',
        'ktp_image_url',
        'account_status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'account_status' => 'string',
        ];
    }

    // ===============================
    // RELATIONSHIPS
    // ===============================

    /**
     * Get the savings transactions for this user.
     */
    public function savingsTransactions()
    {
        return $this->hasMany(SavingsTransaction::class);
    }

    /**
     * Get the loans for this user.
     */
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Get the announcements created by this admin user.
     */
    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'admin_user_id');
    }

    /**
     * Get the savings transactions processed by this admin.
     */
    public function processedSavingsTransactions()
    {
        return $this->hasMany(SavingsTransaction::class, 'processed_by_admin_id');
    }

    /**
     * Get the loans approved by this admin.
     */
    public function approvedLoans()
    {
        return $this->hasMany(Loan::class, 'approved_by_admin_id');
    }

    /**
     * Get the loan installments processed by this admin.
     */
    public function processedLoanInstallments()
    {
        return $this->hasMany(LoanInstallment::class, 'processed_by_admin_id');
    }

    /**
     * Get the audit logs for this user.
     */
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    // ===============================
    // BUSINESS LOGIC METHODS
    // ===============================

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->account_status === 'active';
    }

    /**
     * Check if user is pending verification
     */
    public function isPendingVerification(): bool
    {
        return $this->account_status === 'pending_verification';
    }

    /**
     * Check if user is a member
     */
    public function isMember(): bool
    {
        return $this->hasRole('member');
    }

    /**
     * Check if user is an administrator
     */
    public function isAdministrator(): bool
    {
        return $this->hasRole('administrator');
    }

    /**
     * Check if user is a supervisor
     */
    public function isSupervisor(): bool
    {
        return $this->hasRole('supervisor');
    }

    /**
     * Check if user can access admin panel
     */
    public function canAccessAdminPanel(): bool
    {
        return $this->hasRole(['administrator', 'supervisor']);
    }

    /**
     * Check if user can manage other members
     */
    public function canManageMembers(): bool
    {
        return $this->hasPermissionTo('manage members');
    }

    /**
     * Get user's primary role name
     */
    public function getPrimaryRole(): string
    {
        return $this->roles->first()?->name ?? 'guest';
    }

    /**
     * Assign member role with default permissions
     */
    public function assignMemberRole(): void
    {
        $this->assignRole('member');
    }

    /**
     * Check if user has been assigned a role
     */
    public function hasBeenAssignedRole(): bool
    {
        return $this->roles()->exists();
    }

    /**
     * Check if user has paid principal savings (one-time Rp 100,000)
     */
    public function hasPaidPrincipalSavings(): bool
    {
        return $this->savingsTransactions()
            ->where('savings_type', 'pokok')
            ->where('transaction_type', 'setor')
            ->where('amount', 100000)
            ->whereIn('status', ['completed', 'pending'])
            ->exists();
    }

    /**
     * Get the count of mandatory savings payments (should be monthly Rp 50,000)
     */
    public function getMandatoryPaymentsCount(): int
    {
        return $this->savingsTransactions()
            ->where('savings_type', 'wajib')
            ->where('transaction_type', 'setor')
            ->where('amount', 50000)
            ->where('status', 'completed')
            ->count();
    }

    /**
     * Check if user is current on mandatory savings (based on membership duration)
     */
    public function isCurrentOnMandatorySavings(): bool
    {
        $monthsSinceMembership = $this->created_at->diffInMonths(now());
        $expectedPayments = max(1, $monthsSinceMembership); // At least 1 payment expected
        $actualPayments = $this->getMandatoryPaymentsCount();

        // Must have enough payments AND they must be in sequential order
        return $actualPayments >= $expectedPayments && $this->hasSequentialMandatoryPayments();
    }

    /**
     * Check if user has made mandatory payments in sequential order (no gaps)
     */
    public function hasSequentialMandatoryPayments(): bool
    {
        $memberSince = $this->created_at->startOfMonth();
        $currentMonth = now()->startOfMonth();

        // Get all paid months
        $paidMonths = $this->savingsTransactions()
            ->where('savings_type', 'wajib')
            ->where('transaction_type', 'setor')
            ->where('amount', 50000)
            ->where('status', 'completed')
            ->get()
            ->map(function ($transaction) {
                return $transaction->transaction_date->format('Y-m');
            })
            ->toArray();

        // Check if all months from membership start to current are paid (allowing for future payments)
        $tempMonth = $memberSince->copy();
        while ($tempMonth <= $currentMonth) {
            if (!in_array($tempMonth->format('Y-m'), $paidMonths)) {
                return false; // Found a gap
            }
            $tempMonth->addMonth();
        }

        return true;
    }

    /**
     * Check if user has paid current month's mandatory savings
     */
    public function hasCurrentMonthMandatoryPayment(): bool
    {
        return $this->savingsTransactions()
            ->where('savings_type', 'wajib')
            ->where('transaction_type', 'setor')
            ->where('amount', 50000)
            ->whereIn('status', ['completed', 'pending'])
            ->whereYear('transaction_date', now()->year)
            ->whereMonth('transaction_date', now()->month)
            ->exists();
    }

    /**
     * Legacy method - keep for backward compatibility but redirect to new method
     * @deprecated Use hasPaidPrincipalSavings() instead
     */
    public function hasPaidMandatorySavings(): bool
    {
        return $this->hasPaidPrincipalSavings();
    }

    /**
     * Legacy method - keep for backward compatibility but redirect to new method
     * @deprecated Use getMandatoryPaymentsCount() instead
     */
    public function getPrincipalPaymentsCount(): int
    {
        return $this->getMandatoryPaymentsCount();
    }

    /**
     * Legacy method - keep for backward compatibility but redirect to new method
     * @deprecated Use isCurrentOnMandatorySavings() instead
     */
    public function isCurrentOnPrincipalSavings(): bool
    {
        return $this->isCurrentOnMandatorySavings();
    }
}
