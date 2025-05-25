<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lab extends Model
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
        'file_path',
        'due_date',
        'max_score',
        'equipment',
        'duration',
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
     * Get the course that owns the lab.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the instructor who created the lab.
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Get the submissions for the lab.
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(LabSubmission::class);
    }
}
