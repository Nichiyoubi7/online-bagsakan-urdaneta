<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id', 'seller_id', 'driver_id', 'status', 'delivery_type',
        'payment_method', 'subtotal', 'delivery_fee', 'tip', 'total',
        'total_weight_kg', 'delivery_address', 'delivery_note', 'delivered_at',
        'xendit_invoice_id', 'xendit_invoice_url', 'payment_status',
        'gcash_receipt', 'gcash_ref_no', 'payment_verified', 'payment_verified_at',
        'ai_verification_result',
    ];

    protected $casts = [
        'subtotal'             => 'float',
        'delivery_fee'         => 'float',
        'tip'                  => 'float',
        'total'                => 'float',
        'total_weight_kg'      => 'float',
        'delivered_at'         => 'datetime',
        'payment_verified'     => 'boolean',
        'payment_verified_at'  => 'datetime',
    ];

    public function customer() { return $this->belongsTo(User::class, 'customer_id'); }
    public function seller()   { return $this->belongsTo(User::class, 'seller_id'); }
    public function driver()   { return $this->belongsTo(User::class, 'driver_id'); }
    public function items()    { return $this->hasMany(OrderItem::class); }
}