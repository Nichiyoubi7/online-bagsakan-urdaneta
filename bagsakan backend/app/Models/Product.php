<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'shop_id',
        'category_id',
        'name',
        'description',
        'price',
        'stock',
        'unit',
        'image_url',
        'is_active',
    ];

    // A product belongs to a shop
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    // A product belongs to a category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // A product can have many reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}