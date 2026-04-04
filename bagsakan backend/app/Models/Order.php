<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'address_id',
        'status',
        'payment_method',
        'payment_status',
        'total_amount',
        'notes',
    ];

    // An order belongs to a user (the buyer)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // An order was sent to an address
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    // An order has many order items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // An order has one delivery
    public function delivery()
    {
        return $this->hasOne(Delivery::class);
    }

    // An order can have many reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}