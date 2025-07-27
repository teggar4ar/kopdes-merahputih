<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string $priority
 * @property bool $is_published
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property int $admin_user_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Announcement extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'content',
        'admin_user_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // Add any relevant casts here in the future
        ];
    }

    // ===============================
    // RELATIONSHIPS
    // ===============================

    /**
     * Get the admin user that created this announcement.
     */
    public function adminUser()
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }

    // ===============================
    // SCOPES
    // ===============================

    /**
     * Scope a query to include published announcements.
     * For now, all announcements are considered published.
     */
    public function scopePublished($query)
    {
        return $query; // Return all announcements since we don't have is_published column yet
    }

    /**
     * Scope a query to order by priority and published date.
     */
    public function scopeOrdered($query)
    {
        return $query->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
            ->orderBy('published_at', 'desc');
    }

    // ===============================
    // BUSINESS LOGIC METHODS
    // ===============================

    /**
     * Check if announcement is published.
     */
    public function isPublished(): bool
    {
        return $this->is_published;
    }

    /**
     * Check if announcement is draft.
     */
    public function isDraft(): bool
    {
        return !$this->is_published;
    }

    /**
     * Check if announcement is high priority.
     */
    public function isHighPriority(): bool
    {
        return $this->priority === 'high';
    }

    /**
     * Get priority in Indonesian.
     */
    public function getPriorityIndonesianAttribute(): string
    {
        return match ($this->priority) {
            'high' => 'Tinggi',
            'medium' => 'Sedang',
            'low' => 'Rendah',
            default => ucfirst($this->priority),
        };
    }

    /**
     * Get priority badge class for UI.
     */
    public function getPriorityBadgeClassAttribute(): string
    {
        return match ($this->priority) {
            'high' => 'badge-danger',
            'medium' => 'badge-warning',
            'low' => 'badge-info',
            default => 'badge-secondary',
        };
    }

    /**
     * Get excerpt of content.
     */
    public function getExcerpt(int $length = 150): string
    {
        return strlen($this->content) > $length
            ? substr($this->content, 0, $length) . '...'
            : $this->content;
    }

    /**
     * Publish the announcement.
     */
    public function publish(): void
    {
        $this->update([
            'is_published' => true,
            'published_at' => now(),
        ]);
    }

    /**
     * Unpublish the announcement.
     */
    public function unpublish(): void
    {
        $this->update([
            'is_published' => false,
            'published_at' => null,
        ]);
    }
}
