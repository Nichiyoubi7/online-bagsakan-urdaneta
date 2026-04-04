<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'rating',
        'comment',
    ];

    // A review belongs to a user (the buyer)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A review belongs to a product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // A review belongs to an order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}