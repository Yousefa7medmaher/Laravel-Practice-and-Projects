<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCredit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'credit_balance'
    ];

    protected $casts = [
        'credit_balance' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function addCredit($amount)
    {
        $this->credit_balance += $amount;
        return $this->save();
    }

    public function deductCredit($amount)
    {
        if ($this->credit_balance >= $amount) {
            $this->credit_balance -= $amount;
            return $this->save();
        }
        return false;
    }
} 