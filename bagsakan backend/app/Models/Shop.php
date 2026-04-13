<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'logo_url',
        'is_active',
    ];

    // A shop belongs to a user (the seller)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A shop has many products
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // A shop has many order items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}