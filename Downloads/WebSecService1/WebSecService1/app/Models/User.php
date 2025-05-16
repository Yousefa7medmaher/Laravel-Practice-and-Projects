<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /** @use HasFactory<\Database\Factories\UserFactory> */

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'profile_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function credit()
    {
        return $this->hasOne(UserCredit::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function getCreditBalance()
    {
        if (!$this->credit) {
            $this->credit()->create(['credit_balance' => 0]);
            $this->refresh();
        }
        return $this->credit->credit_balance;
    }

    public function addCredit($amount)
    {
        if (!$this->credit) {
            $this->credit()->create(['credit_balance' => 0]);
            $this->refresh();  // Refresh the model to load the new relationship
        }
        return $this->credit->addCredit($amount);
    }

    public function deductCredit($amount)
    {
        if (!$this->credit) {
            return false;
        }
        return $this->credit->deductCredit($amount);
    }

    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            return asset('storage/' . $this->profile_image);
        }
        return asset('images/default-profile.png');
    }

    public function purchaseProduct(Product $product, $quantity)
    {
        if (!$this->hasRole('customer')) {
            return false;
        }

        $totalPrice = $product->price * $quantity;

        if (!$product->isInStock() || $quantity > $product->stock_quantity) {
            return false;
        }

        if ($this->getCreditBalance() < $totalPrice) {
            return false;
        }

        return \DB::transaction(function () use ($product, $quantity, $totalPrice) {
            if (!$product->decreaseStock($quantity)) {
                return false;
            }

            if (!$this->deductCredit($totalPrice)) {
                return false;
            }

            return $this->purchases()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'total_price' => $totalPrice
            ]);
        });
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            if ($user->email === 'admin@example.com') {
                $user->assignRole('admin');
            }
        });
    }
}
