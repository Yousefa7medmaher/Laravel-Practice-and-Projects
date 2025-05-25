<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Notification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'data',
        'is_read',
        'read_at',
        'action_url',
        'icon',
        'priority',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user that owns the notification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for read notifications.
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope for notifications by type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for notifications by priority.
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope for non-expired notifications.
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', Carbon::now());
        });
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => Carbon::now(),
        ]);
    }

    /**
     * Mark notification as unread.
     */
    public function markAsUnread()
    {
        $this->update([
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    /**
     * Check if notification is expired.
     */
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Get formatted time ago.
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get notification icon based on type.
     */
    public function getDefaultIconAttribute()
    {
        if ($this->icon) {
            return $this->icon;
        }

        return match($this->type) {
            'assignment' => 'fas fa-tasks',
            'quiz' => 'fas fa-question-circle',
            'grade' => 'fas fa-star',
            'course' => 'fas fa-book',
            'success' => 'fas fa-check-circle',
            'warning' => 'fas fa-exclamation-triangle',
            'error' => 'fas fa-times-circle',
            'system' => 'fas fa-cog',
            default => 'fas fa-info-circle',
        };
    }

    /**
     * Get notification color based on type and priority.
     */
    public function getColorClassAttribute()
    {
        if ($this->priority === 'urgent') {
            return 'text-red-600 bg-red-50 border-red-200';
        }

        return match($this->type) {
            'assignment' => 'text-blue-600 bg-blue-50 border-blue-200',
            'quiz' => 'text-purple-600 bg-purple-50 border-purple-200',
            'grade' => 'text-green-600 bg-green-50 border-green-200',
            'course' => 'text-indigo-600 bg-indigo-50 border-indigo-200',
            'success' => 'text-green-600 bg-green-50 border-green-200',
            'warning' => 'text-yellow-600 bg-yellow-50 border-yellow-200',
            'error' => 'text-red-600 bg-red-50 border-red-200',
            'system' => 'text-gray-600 bg-gray-50 border-gray-200',
            default => 'text-blue-600 bg-blue-50 border-blue-200',
        };
    }
}
