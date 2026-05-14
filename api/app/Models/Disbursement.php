<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Disbursement extends Model
{
    protected $fillable = [
        'order_id',
        'seller_id',
        'amount',
        'seller_gcash',
        'status',
        'released_at',
    ];

    protected $casts = [
        'amount'      => 'decimal:2',
        'released_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}

