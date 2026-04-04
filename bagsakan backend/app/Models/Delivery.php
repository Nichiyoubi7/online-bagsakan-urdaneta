<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'status',
        'picked_up_at',
        'delivered_at',
    ];

    // A delivery belongs to an order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // A delivery belongs to a user (the driver)
    public function driver()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}