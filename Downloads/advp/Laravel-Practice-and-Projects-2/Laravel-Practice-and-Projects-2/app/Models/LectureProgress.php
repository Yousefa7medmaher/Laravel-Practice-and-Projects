<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LectureProgress extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lecture_id',
        'user_id',
        'progress_percentage',
        'completed',
        'attended',
        'first_accessed_at',
        'last_accessed_at',
        'completed_at',
        'time_spent', // in minutes
        'total_duration', // total time spent across all sessions
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'progress_percentage' => 'integer',
        'completed' => 'boolean',
        'attended' => 'boolean',
        'first_accessed_at' => 'datetime',
        'last_accessed_at' => 'datetime',
        'completed_at' => 'datetime',
        'time_spent' => 'integer',
        'total_duration' => 'integer',
    ];

    /**
     * Get the lecture that owns the progress.
     */
    public function lecture(): BelongsTo
    {
        return $this->belongsTo(Lecture::class);
    }

    /**
     * Get the user that owns the progress.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the lecture is completed
     */
    public function isCompleted(): bool
    {
        return $this->completed || $this->progress_percentage >= 100;
    }

    /**
     * Get progress status as string
     */
    public function getStatusAttribute(): string
    {
        if ($this->completed || $this->progress_percentage >= 100) {
            return 'completed';
        } elseif ($this->progress_percentage > 0) {
            return 'in_progress';
        } else {
            return 'not_started';
        }
    }

    /**
     * Update progress and auto-complete if 100%
     */
    public function updateProgress(int $percentage, int $timeSpent = 0): void
    {
        $this->progress_percentage = min(100, max(0, $percentage));
        $wasCompleted = $this->completed;
        $this->completed = $this->progress_percentage >= 100;
        $this->attended = $this->progress_percentage >= 80; // Consider attended at 80%

        // Set first access time if not set
        if (!$this->first_accessed_at) {
            $this->first_accessed_at = now();
        }

        $this->last_accessed_at = now();

        // Set completion time when first completed
        if ($this->completed && !$wasCompleted) {
            $this->completed_at = now();
        }

        // Update time tracking
        if ($timeSpent > 0) {
            $this->time_spent = $timeSpent;
            $this->total_duration = ($this->total_duration ?? 0) + $timeSpent;
        }

        $this->save();
    }

    /**
     * Get attendance status as string
     */
    public function getAttendanceStatusAttribute(): string
    {
        if ($this->completed) {
            return 'completed';
        } elseif ($this->attended) {
            return 'attended';
        } elseif ($this->progress_percentage > 0) {
            return 'in_progress';
        } else {
            return 'not_started';
        }
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute(): string
    {
        if (!$this->total_duration) return '0 min';

        $hours = floor($this->total_duration / 60);
        $minutes = $this->total_duration % 60;

        if ($hours > 0) {
            return "{$hours}h {$minutes}m";
        }

        return "{$minutes}m";
    }

    /**
     * Get attendance badge info
     */
    public function getAttendanceBadgeAttribute(): array
    {
        $status = $this->attendance_status;

        $badges = [
            'completed' => ['text' => 'Completed', 'color' => 'green', 'icon' => 'check-circle'],
            'attended' => ['text' => 'Attended', 'color' => 'blue', 'icon' => 'eye'],
            'in_progress' => ['text' => 'In Progress', 'color' => 'yellow', 'icon' => 'clock'],
            'not_started' => ['text' => 'Not Started', 'color' => 'gray', 'icon' => 'circle'],
        ];

        return $badges[$status] ?? $badges['not_started'];
    }
}
