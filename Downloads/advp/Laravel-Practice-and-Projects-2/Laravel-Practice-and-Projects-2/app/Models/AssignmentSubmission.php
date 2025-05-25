<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class AssignmentSubmission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'assignment_id',
        'user_id',
        'submission_text',
        'submission_data',
        'status',
        'submitted_at',
        'grade',
        'feedback',
        'graded_by',
        'graded_at',
        'is_late',
        'attempt_number',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'submission_data' => 'array',
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
        'is_late' => 'boolean',
        'grade' => 'decimal:2',
    ];

    /**
     * Get the assignment that owns the submission.
     */
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    /**
     * Get the user (student) that owns the submission.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the instructor who graded the submission.
     */
    public function gradedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    /**
     * Get submission status badge info
     */
    public function getStatusBadgeAttribute(): array
    {
        $status = $this->status;

        $badges = [
            'draft' => ['text' => 'Draft', 'color' => 'gray', 'icon' => 'edit'],
            'submitted' => ['text' => 'Submitted', 'color' => 'blue', 'icon' => 'check'],
            'graded' => ['text' => 'Graded', 'color' => 'green', 'icon' => 'star'],
            'returned' => ['text' => 'Returned', 'color' => 'yellow', 'icon' => 'undo'],
        ];

        // Handle overdue status
        if ($this->assignment && $this->assignment->due_date && now() > $this->assignment->due_date && $status !== 'graded') {
            if ($status === 'submitted') {
                $badges['submitted']['text'] = 'Late Submission';
                $badges['submitted']['color'] = 'orange';
            } else {
                return ['text' => 'Overdue', 'color' => 'red', 'icon' => 'exclamation-triangle'];
            }
        }

        return $badges[$status] ?? $badges['draft'];
    }

    /**
     * Get formatted submission date
     */
    public function getFormattedSubmissionDateAttribute(): string
    {
        if (!$this->submitted_at) return 'Not submitted';

        return $this->submitted_at->format('M j, Y \a\t g:i A');
    }

    /**
     * Get grade display
     */
    public function getGradeDisplayAttribute(): ?string
    {
        if ($this->grade === null) return null;

        return "{$this->grade}%";
    }

    /**
     * Check if submission is overdue
     */
    public function isOverdue(): bool
    {
        if (!$this->assignment || !$this->assignment->due_date) return false;

        return now() > $this->assignment->due_date && $this->status !== 'graded';
    }

    /**
     * Check if submission is late
     */
    public function isLateSubmission(): bool
    {
        if (!$this->submitted_at || !$this->assignment || !$this->assignment->due_date) return false;

        return $this->submitted_at > $this->assignment->due_date;
    }

    /**
     * Get the files for the submission.
     */
    public function files(): HasMany
    {
        return $this->hasMany(SubmissionFile::class, 'submission_id');
    }

    /**
     * Get the primary file for the submission.
     */
    public function primaryFile()
    {
        return $this->files()->where('is_primary', true)->first();
    }

    /**
     * Check if the submission is late.
     */
    public function checkIfLate(): bool
    {
        if (!$this->submitted_at || !$this->assignment->due_date) {
            return false;
        }

        return $this->submitted_at->isAfter($this->assignment->due_date);
    }

    /**
     * Get the submission status with human-readable text.
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'submitted' => 'Submitted',
            'graded' => 'Graded',
            'returned' => 'Returned for Revision',
            default => 'Unknown'
        };
    }

    /**
     * Get the grade percentage.
     */
    public function getGradePercentageAttribute(): ?float
    {
        if (!$this->grade || !$this->assignment->max_score) {
            return null;
        }

        return round(($this->grade / $this->assignment->max_score) * 100, 1);
    }

    /**
     * Get the letter grade.
     */
    public function getLetterGradeAttribute(): ?string
    {
        $percentage = $this->grade_percentage;

        if ($percentage === null) {
            return null;
        }

        return match(true) {
            $percentage >= 90 => 'A',
            $percentage >= 80 => 'B',
            $percentage >= 70 => 'C',
            $percentage >= 60 => 'D',
            default => 'F'
        };
    }

    /**
     * Get formatted submission time.
     */
    public function getFormattedSubmittedAtAttribute(): ?string
    {
        if (!$this->submitted_at) {
            return null;
        }

        return $this->submitted_at->format('M j, Y \a\t g:i A');
    }

    /**
     * Get time until due date or time since due date.
     */
    public function getDueDateStatusAttribute(): array
    {
        if (!$this->assignment->due_date) {
            return ['status' => 'no_due_date', 'text' => 'No due date set'];
        }

        $now = Carbon::now();
        $dueDate = $this->assignment->due_date;

        if ($this->status === 'submitted' || $this->status === 'graded') {
            if ($this->is_late) {
                $lateDuration = $this->submitted_at->diffForHumans($dueDate);
                return ['status' => 'submitted_late', 'text' => "Submitted {$lateDuration} after due date"];
            } else {
                return ['status' => 'submitted_on_time', 'text' => 'Submitted on time'];
            }
        }

        if ($now->isAfter($dueDate)) {
            $overdueDuration = $now->diffForHumans($dueDate);
            return ['status' => 'overdue', 'text' => "Overdue by {$overdueDuration}"];
        } else {
            $timeLeft = $now->diffForHumans($dueDate);
            return ['status' => 'upcoming', 'text' => "Due {$timeLeft}"];
        }
    }

    /**
     * Scope to get submissions for a specific assignment.
     */
    public function scopeForAssignment($query, $assignmentId)
    {
        return $query->where('assignment_id', $assignmentId);
    }

    /**
     * Scope to get submissions for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get only submitted submissions.
     */
    public function scopeSubmitted($query)
    {
        return $query->whereIn('status', ['submitted', 'graded', 'returned']);
    }

    /**
     * Scope to get only graded submissions.
     */
    public function scopeGraded($query)
    {
        return $query->where('status', 'graded');
    }
}
