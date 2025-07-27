<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string $action
 * @property string|null $model_type
 * @property int|null $model_id
 * @property array|null $old_values
 * @property array|null $new_values
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class AuditLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
        ];
    }

    // ===============================
    // RELATIONSHIPS
    // ===============================

    /**
     * Get the user that performed the action.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the model that was affected (polymorphic relationship).
     */
    public function model()
    {
        return $this->morphTo();
    }

    // ===============================
    // SCOPES
    // ===============================

    /**
     * Scope a query to filter by user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to filter by action.
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope a query to filter by model type.
     */
    public function scopeByModelType($query, $modelType)
    {
        return $query->where('model_type', $modelType);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope a query to get recent logs.
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // ===============================
    // BUSINESS LOGIC METHODS
    // ===============================

    /**
     * Get action in Indonesian.
     */
    public function getActionIndonesianAttribute(): string
    {
        return match ($this->action) {
            'created' => 'Dibuat',
            'updated' => 'Diperbarui',
            'deleted' => 'Dihapus',
            'login' => 'Masuk',
            'logout' => 'Keluar',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'published' => 'Dipublikasikan',
            'unpublished' => 'Tidak Dipublikasikan',
            default => ucfirst($this->action),
        };
    }

    /**
     * Get model name in Indonesian.
     */
    public function getModelNameIndonesianAttribute(): string
    {
        if (!$this->model_type) {
            return 'Sistem';
        }

        $modelName = class_basename($this->model_type);

        return match ($modelName) {
            'User' => 'Pengguna',
            'SavingsTransaction' => 'Transaksi Simpanan',
            'Loan' => 'Pinjaman',
            'LoanInstallment' => 'Angsuran Pinjaman',
            'Announcement' => 'Pengumuman',
            'AuditLog' => 'Log Audit',
            default => $modelName,
        };
    }

    /**
     * Get formatted description of the action.
     */
    public function getDescriptionAttribute(): string
    {
        $userName = $this->user ? $this->user->name : 'Sistem';
        $action = $this->action_indonesian;
        $model = $this->model_name_indonesian;

        if ($this->model_id) {
            return "{$userName} {$action} {$model} ID #{$this->model_id}";
        }

        return "{$userName} {$action} {$model}";
    }

    /**
     * Get changes summary.
     */
    public function getChangesSummary(): array
    {
        $changes = [];

        if ($this->old_values && $this->new_values) {
            foreach ($this->new_values as $key => $newValue) {
                $oldValue = $this->old_values[$key] ?? null;
                if ($oldValue != $newValue) {
                    $changes[$key] = [
                        'old' => $oldValue,
                        'new' => $newValue,
                    ];
                }
            }
        }

        return $changes;
    }

    /**
     * Static method to log an action.
     */
    public static function logAction(
        string $action,
        ?Model $model = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?int $userId = null
    ): void {
        $userId = $userId ?? auth()->id();

        static::create([
            'user_id' => $userId,
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Static method to log user login.
     */
    public static function logLogin(int $userId): void
    {
        static::logAction('login', userId: $userId);
    }

    /**
     * Static method to log user logout.
     */
    public static function logLogout(int $userId): void
    {
        static::logAction('logout', userId: $userId);
    }
}
