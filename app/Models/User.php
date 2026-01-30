<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'family_name',
        'last_name',
        'family_name_kana',
        'last_name_kana',
        'email',
        'password',
        'postal_code',
        'address',
        'phone_number',
        'is_deleted',
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
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::substr($this->family_name, 0, 1)
             . Str::substr($this->last_name, 0, 1);
    }

    public function fullName(): Attribute
    {
        return Attribute::get(
            fn () => ($this->family_name ?? '') . ' ' . ($this->last_name ?? ''),
        );
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /*
    public function deliveryInfos()
    {
        return $this->hasMany(DeliveryInfo::class);
    }
    */
}
