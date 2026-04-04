<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'label',
        'street',
        'barangay',
        'city',
        'province',
        'zip_code',
        'is_default',
    ];

    // An address belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}