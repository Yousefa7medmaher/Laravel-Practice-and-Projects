<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Assignment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'course_id',
        'instructor_id',
        'title',
        'description',
        'instructions',
        'due_date',
        'max_score',
        'points', // Alternative field name for max_score
        'file_path',
        'allow_late_submission',
        'is_visible',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'due_date' => 'datetime',
        'allow_late_submission' => 'boolean',
        'is_visible' => 'boolean',
    ];

    /**
     * Get the course that owns the assignment.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the submissions for the assignment.
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    /**
     * Get the instructor who created the assignment.
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Get the submitted submissions for the assignment.
     */
    public function submittedSubmissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class)
            ->whereIn('status', ['submitted', 'graded', 'returned']);
    }

    /**
     * Get submission for a specific user.
     */
    public function getSubmissionForUser($userId)
    {
        return $this->submissions()
            ->where('user_id', $userId)
            ->orderBy('attempt_number', 'desc')
            ->first();
    }

    /**
     * Check if assignment is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date && Carbon::now()->isAfter($this->due_date);
    }

    /**
     * Get formatted due date.
     */
    public function getFormattedDueDateAttribute(): string
    {
        if (!$this->due_date) {
            return 'No due date';
        }

        return $this->due_date->format('M j, Y \a\t g:i A');
    }

    /**
     * Get time until due date or time since due date.
     */
    public function getDueDateStatusAttribute(): array
    {
        if (!$this->due_date) {
            return ['status' => 'no_due_date', 'text' => 'No due date set'];
        }

        $now = Carbon::now();

        if ($now->isAfter($this->due_date)) {
            $overdueDuration = $now->diffForHumans($this->due_date);
            return ['status' => 'overdue', 'text' => "Overdue by {$overdueDuration}"];
        } else {
            $timeLeft = $now->diffForHumans($this->due_date);
            return ['status' => 'upcoming', 'text' => "Due {$timeLeft}"];
        }
    }

    /**
     * Get submission statistics for the assignment.
     */
    public function getSubmissionStatsAttribute(): array
    {
        $totalSubmissions = $this->submittedSubmissions()->count();
        $gradedSubmissions = $this->submissions()->where('status', 'graded')->count();
        $lateSubmissions = $this->submissions()->where('is_late', true)->count();

        return [
            'total_submissions' => $totalSubmissions,
            'graded_submissions' => $gradedSubmissions,
            'late_submissions' => $lateSubmissions,
            'pending_grading' => $totalSubmissions - $gradedSubmissions,
        ];
    }

    /**
     * Check if assignment accepts submissions.
     */
    public function acceptsSubmissions(): bool
    {
        // You can add more complex logic here (e.g., submission cutoff dates)
        return true;
    }
}
