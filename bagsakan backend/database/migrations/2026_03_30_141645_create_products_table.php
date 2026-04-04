<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
     Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
        $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
        $table->string('name');
        $table->text('description')->nullable();
        $table->decimal('price', 10, 2); // e.g. 45.00
        $table->integer('stock')->default(0);
        $table->string('unit', 50)->default('piece'); // kg, bundle, piece
        $table->string('image_url', 500)->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
