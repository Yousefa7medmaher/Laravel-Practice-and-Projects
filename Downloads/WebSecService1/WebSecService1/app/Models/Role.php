<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user');
    }

    public function hasUser($userId)
    {
        return $this->users()->where('user_id', $userId)->exists();
    }
} 