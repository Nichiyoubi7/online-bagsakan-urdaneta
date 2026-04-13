<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'product_id',
        'customer_id',
        'order_id',
        'rating',
        'comment',
    ];

    // A review belongs to a customer (user)
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
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