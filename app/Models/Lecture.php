<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'title',
        'description',
        'content',
        'video_url',
        'file_path',
        'order',
    ];

    /**
     * Get the course that owns the lecture.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
