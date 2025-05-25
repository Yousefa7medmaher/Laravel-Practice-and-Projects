<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAttempt extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'quiz_id',
        'user_id',
        'attempt_number',
        'status',
        'started_at',
        'completed_at',
        'expires_at',
        'answers',
        'score',
        'feedback',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'expires_at' => 'datetime',
        'answers' => 'array',
        'score' => 'decimal:2',
    ];

    /**
     * Get the quiz that owns the attempt.
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Get the user that owns the attempt.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the attempt is active
     */
    public function isActive(): bool
    {
        return $this->status === 'in_progress' &&
               (!$this->expires_at || now() <= $this->expires_at);
    }

    /**
     * Check if the attempt has expired
     */
    public function hasExpired(): bool
    {
        return $this->expires_at && now() > $this->expires_at;
    }

    /**
     * Get the duration of the attempt in minutes
     */
    public function getDurationAttribute(): ?int
    {
        if (!$this->started_at || !$this->completed_at) {
            return null;
        }

        return $this->started_at->diffInMinutes($this->completed_at);
    }

    /**
     * Get the remaining time in minutes
     */
    public function getRemainingTimeAttribute(): ?int
    {
        if (!$this->expires_at || $this->status !== 'in_progress') {
            return null;
        }

        $remaining = now()->diffInMinutes($this->expires_at, false);
        return max(0, $remaining);
    }

    /**
     * Get attempt status badge info
     */
    public function getStatusBadgeAttribute(): array
    {
        $status = $this->status;

        $badges = [
            'in_progress' => ['text' => 'In Progress', 'color' => 'blue', 'icon' => 'clock'],
            'completed' => ['text' => 'Completed', 'color' => 'green', 'icon' => 'check-circle'],
            'expired' => ['text' => 'Expired', 'color' => 'red', 'icon' => 'times-circle'],
            'abandoned' => ['text' => 'Abandoned', 'color' => 'gray', 'icon' => 'ban'],
        ];

        return $badges[$status] ?? $badges['completed'];
    }

    /**
     * Get formatted completion date
     */
    public function getFormattedCompletionDateAttribute(): string
    {
        if (!$this->completed_at) return 'Not completed';

        return $this->completed_at->format('M j, Y \a\t g:i A');
    }

    /**
     * Get score display with percentage
     */
    public function getScoreDisplayAttribute(): string
    {
        if ($this->score === null) return 'No score';

        return "{$this->score}%";
    }

    /**
     * Get performance level based on score
     */
    public function getPerformanceLevelAttribute(): array
    {
        if ($this->score === null) {
            return ['level' => 'none', 'color' => 'gray', 'text' => 'No Score'];
        }

        if ($this->score >= 90) {
            return ['level' => 'excellent', 'color' => 'green', 'text' => 'Excellent'];
        } elseif ($this->score >= 80) {
            return ['level' => 'good', 'color' => 'blue', 'text' => 'Good'];
        } elseif ($this->score >= 70) {
            return ['level' => 'satisfactory', 'color' => 'yellow', 'text' => 'Satisfactory'];
        } elseif ($this->score >= 60) {
            return ['level' => 'needs_improvement', 'color' => 'orange', 'text' => 'Needs Improvement'];
        } else {
            return ['level' => 'poor', 'color' => 'red', 'text' => 'Poor'];
        }
    }
}
