<?php

use App\Models\Product;

Route::get('/test-model', function() {
    return Product::all(); // get all products
});