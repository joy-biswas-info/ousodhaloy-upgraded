<?php
// ============================================================
// app/Models/User.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'avatar',
        'role',
        'is_active',
        'referral_code',
        'referred_by',
        'phone_verified_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    public function isManager(): bool
    {
        return in_array($this->role, ['admin', 'manager']);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }
    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }
    public function loyaltyPoints(): HasMany
    {
        return $this->hasMany(LoyaltyPoint::class);
    }

    public function getTotalLoyaltyPointsAttribute(): int
    {
        return $this->loyaltyPoints()->sum('points');
    }

    public function getDefaultAddressAttribute()
    {
        return $this->addresses()->where('is_default', true)->first()
            ?? $this->addresses()->latest()->first();
    }
}