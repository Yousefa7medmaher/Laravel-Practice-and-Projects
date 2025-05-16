<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'total_price',
        'delivery_method',
        'delivery_address',
        'pickup_branch',
        'color',
        'payment_method',
        'card_last4',
        'order_status',
        'estimated_delivery_time',
        'delivered_at'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'total_price' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
} 