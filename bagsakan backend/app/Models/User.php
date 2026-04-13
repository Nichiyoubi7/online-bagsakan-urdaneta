<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // A user can have many addresses
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    // A user (seller) can have one shop
    public function shop()
    {
        return $this->hasOne(Shop::class);
    }

    // A user (buyer) can have one cart
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    // A user (buyer) can have many orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // A user (buyer) can write many reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // A user (driver) can have many deliveries
    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }
}