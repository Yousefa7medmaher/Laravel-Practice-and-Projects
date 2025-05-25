<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lecture extends Model
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
        'content',
        'objectives',
        'video_url',
        'file_path',
        'duration',
        'scheduled_date',
        'order',
        'is_published',
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
        'scheduled_date' => 'datetime',
        'is_published' => 'boolean',
        'is_visible' => 'boolean',
    ];

    /**
     * Get the course that owns the lecture.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the instructor who created the lecture.
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Get the progress records for this lecture.
     */
    public function progress(): HasMany
    {
        return $this->hasMany(LectureProgress::class);
    }
}
