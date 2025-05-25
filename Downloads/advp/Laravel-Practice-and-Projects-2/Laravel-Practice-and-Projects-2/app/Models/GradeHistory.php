<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeHistory extends Model
{
    protected $fillable = [
        'submission_id',
        'student_id',
        'instructor_id',
        'course_id',
        'grade',
        'feedback',
        'action',
        'graded_at'
    ];

    protected $casts = [
        'grade' => 'decimal:2',
        'graded_at' => 'datetime'
    ];

    public $timestamps = false; // We use graded_at instead

    // Relationships
    public function submission()
    {
        return $this->belongsTo(AssignmentSubmission::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Scopes
    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeByInstructor($query, $instructorId)
    {
        return $query->where('instructor_id', $instructorId);
    }

    public function scopeByCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    // Static method for logging grades
    public static function logGrade($submissionId, $studentId, $instructorId, $courseId, $grade, $feedback, $action = 'graded')
    {
        return self::create([
            'submission_id' => $submissionId,
            'student_id' => $studentId,
            'instructor_id' => $instructorId,
            'course_id' => $courseId,
            'grade' => $grade,
            'feedback' => $feedback,
            'action' => $action,
            'graded_at' => now()
        ]);
    }
}
