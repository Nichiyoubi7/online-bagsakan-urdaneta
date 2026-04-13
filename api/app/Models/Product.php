<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        "user_id", "category_id", "name", "sku", "description",
        "price", "original_price", "stock", "weight_kg", "status",
        "image", "images",
    ];

    protected $casts = [
        "images" => "array",
        "price" => "float",
        "original_price" => "float",
        "weight_kg" => "float",
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
{
    return $this->hasMany(ProductImage::class)->orderBy('order');
}



}