<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'imgProfilePath',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the courses that the user is enrolled in.
     */
    public function enrolledCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_user')
            ->withPivot('status', 'enrolled_at', 'completed_at', 'final_grade')
            ->withTimestamps();
    }

    /**
     * Get the courses that the user teaches (as an instructor).
     */
    public function taughtCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    /**
     * Alias for taughtCourses for consistency with manager APIs
     */
    public function assignedCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    /**
     * Get submissions graded by this instructor
     */
    public function gradedSubmissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class, 'graded_by');
    }

    /**
     * Get submissions made by this student
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class, 'user_id');
    }

    /**
     * Get enrollments for this student (using course_user pivot table)
     */
    public function enrollments(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_user')
            ->withPivot('status', 'enrolled_at', 'completed_at', 'final_grade')
            ->withTimestamps();
    }

    /**
     * Get activity logs for this user
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(UserActivityLog::class);
    }

    /**
     * Get the notifications for the user.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get quiz attempts for this student
     */
    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Get lecture progress for this student
     */
    public function lectureProgress(): HasMany
    {
        return $this->hasMany(LectureProgress::class);
    }

    /**
     * Get all lectures created by this instructor
     */
    public function createdLectures(): HasMany
    {
        return $this->hasMany(Lecture::class, 'instructor_id');
    }

    /**
     * Get all assignments created by this instructor
     */
    public function createdAssignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'instructor_id');
    }

    /**
     * Get all quizzes created by this instructor
     */
    public function createdQuizzes(): HasMany
    {
        return $this->hasMany(Quiz::class, 'instructor_id');
    }

    /**
     * Get all labs created by this instructor
     */
    public function createdLabs(): HasMany
    {
        return $this->hasMany(Lab::class, 'instructor_id');
    }

    /**
     * Get all materials uploaded by this instructor
     */
    public function uploadedMaterials(): HasMany
    {
        return $this->hasMany(Material::class, 'instructor_id');
    }

    /**
     * Get course assignments for this instructor
     */
    public function courseAssignments(): HasMany
    {
        return $this->hasMany(CourseAssignment::class, 'instructor_id');
    }

    /**
     * Get active course assignments for this instructor
     */
    public function activeCourseAssignments(): HasMany
    {
        return $this->hasMany(CourseAssignment::class, 'instructor_id')
                    ->where('is_active', true);
    }

    /**
     * Get all students enrolled in instructor's courses
     */
    public function instructorStudents()
    {
        return User::whereHas('enrolledCourses', function ($query) {
            $query->where('instructor_id', $this->id);
        })->where('role', 'student');
    }

    /**
     * Get comprehensive instructor statistics
     */
    public function getInstructorStatsAttribute()
    {
        if ($this->role !== 'instructor') {
            return null;
        }

        return [
            'total_courses' => $this->taughtCourses()->count(),
            'active_courses' => $this->taughtCourses()->where('status', 'active')->count(),
            'total_students' => $this->instructorStudents()->count(),
            'total_lectures' => $this->createdLectures()->count(),
            'total_assignments' => $this->createdAssignments()->count(),
            'total_quizzes' => $this->createdQuizzes()->count(),
            'total_labs' => $this->createdLabs()->count(),
            'total_materials' => $this->uploadedMaterials()->count(),
            'graded_submissions' => $this->gradedSubmissions()->count(),
            'pending_submissions' => AssignmentSubmission::whereHas('assignment.course', function ($query) {
                $query->where('instructor_id', $this->id);
            })->where('status', 'submitted')->count(),
        ];
    }

    /**
     * Calculate the user's GPA based on completed courses.
     */
    public function calculateGPA()
    {
        $completedCourses = $this->enrolledCourses()
            ->wherePivot('status', 'completed')
            ->wherePivotNotNull('final_grade')
            ->get();

        if ($completedCourses->isEmpty()) {
            return null; // No completed courses
        }

        $totalGradePoints = 0;
        $totalCreditHours = 0;

        foreach ($completedCourses as $course) {
            $grade = $course->pivot->final_grade;
            $creditHours = $course->credit_hours ?? 3;

            // Convert percentage to GPA points (assuming 4.0 scale)
            $gradePoints = $this->convertPercentageToGPA($grade);

            $totalGradePoints += $gradePoints * $creditHours;
            $totalCreditHours += $creditHours;
        }

        return $totalCreditHours > 0 ? $totalGradePoints / $totalCreditHours : null;
    }

    /**
     * Convert percentage grade to GPA points.
     */
    private function convertPercentageToGPA($percentage)
    {
        if ($percentage >= 90) return 4.0;
        if ($percentage >= 80) return 3.0;
        if ($percentage >= 70) return 2.0;
        if ($percentage >= 60) return 1.0;
        return 0.0;
    }

    /**
     * Get the current credit hours for enrolled courses.
     */
    public function getCurrentCreditHours()
    {
        return $this->enrolledCourses()
            ->wherePivot('status', 'enrolled')
            ->sum('credit_hours') ?? 0;
    }

    /**
     * Get the maximum allowed credit hours based on GPA.
     */
    public function getMaxCreditHours()
    {
        $gpa = $this->calculateGPA();

        if ($gpa === null) {
            return 15; // Default for new students with no completed courses
        }

        if ($gpa > 3.0) {
            return 21; // High GPA students can take up to 21 credits
        } elseif ($gpa > 2.0) {
            return 18; // Good GPA students can take up to 18 credits
        } else {
            return 15; // Lower GPA students limited to 15 credits
        }
    }

    /**
     * Check if the user can enroll in a course based on credit hours.
     */
    public function canEnrollInCourse($courseId)
    {
        $course = Course::find($courseId);
        if (!$course) {
            return false;
        }

        // Check if already enrolled
        if ($this->enrolledCourses()->where('course_id', $courseId)->exists()) {
            return false;
        }

        $currentCredits = $this->getCurrentCreditHours();
        $maxCredits = $this->getMaxCreditHours();
        $courseCredits = $course->credit_hours ?? 3;

        return ($currentCredits + $courseCredits) <= $maxCredits;
    }

    /**
     * Get enrollment statistics for the student.
     */
    public function getEnrollmentStats()
    {
        $enrolledCourses = $this->enrolledCourses()->get();
        $completedCourses = $enrolledCourses->where('pivot.status', 'completed');
        $inProgressCourses = $enrolledCourses->where('pivot.status', 'enrolled');

        return [
            'total_courses' => $enrolledCourses->count(),
            'completed_courses' => $completedCourses->count(),
            'in_progress_courses' => $inProgressCourses->count(),
            'total_credits' => $enrolledCourses->sum('credit_hours'),
            'current_credits' => $this->getCurrentCreditHours(),
            'max_credits' => $this->getMaxCreditHours(),
            'gpa' => $this->calculateGPA(),
        ];
    }
}

