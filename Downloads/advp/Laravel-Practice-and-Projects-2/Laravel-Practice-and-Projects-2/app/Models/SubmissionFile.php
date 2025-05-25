<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class SubmissionFile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'submission_id',
        'original_name',
        'stored_name',
        'file_path',
        'mime_type',
        'file_size',
        'file_extension',
        'is_primary',
        'order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_primary' => 'boolean',
        'file_size' => 'integer',
    ];

    /**
     * Get the submission that owns the file.
     */
    public function submission(): BelongsTo
    {
        return $this->belongsTo(AssignmentSubmission::class, 'submission_id');
    }

    /**
     * Get the formatted file size.
     */
    public function getFormattedFileSizeAttribute(): string
    {
        $bytes = $this->file_size;

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * Get the download URL for the file.
     */
    public function getDownloadUrlAttribute(): string
    {
        return route('api.submission.file.download', [
            'submissionId' => $this->submission_id,
            'fileId' => $this->id
        ]);
    }

    /**
     * Get the file icon based on file type.
     */
    public function getFileIconAttribute(): string
    {
        return match($this->file_extension) {
            'pdf' => 'fas fa-file-pdf text-red-500',
            'doc', 'docx' => 'fas fa-file-word text-blue-500',
            'xls', 'xlsx' => 'fas fa-file-excel text-green-500',
            'ppt', 'pptx' => 'fas fa-file-powerpoint text-orange-500',
            'txt' => 'fas fa-file-alt text-gray-500',
            'zip', 'rar', '7z' => 'fas fa-file-archive text-purple-500',
            'jpg', 'jpeg', 'png', 'gif', 'bmp' => 'fas fa-file-image text-pink-500',
            'mp4', 'avi', 'mov', 'wmv' => 'fas fa-file-video text-indigo-500',
            'mp3', 'wav', 'ogg' => 'fas fa-file-audio text-yellow-500',
            default => 'fas fa-file text-gray-400'
        };
    }

    /**
     * Check if the file exists in storage.
     */
    public function exists(): bool
    {
        return Storage::exists($this->file_path);
    }

    /**
     * Get the full storage path.
     */
    public function getFullPathAttribute(): string
    {
        return Storage::path($this->file_path);
    }

    /**
     * Delete the file from storage.
     */
    public function deleteFile(): bool
    {
        if ($this->exists()) {
            return Storage::delete($this->file_path);
        }
        return true;
    }

    /**
     * Boot method to handle model events.
     */
    protected static function boot()
    {
        parent::boot();

        // Delete file from storage when model is deleted
        static::deleting(function ($file) {
            $file->deleteFile();
        });
    }

    /**
     * Scope to get primary files only.
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope to order files by their order field.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at');
    }
}
