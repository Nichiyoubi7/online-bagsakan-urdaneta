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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('driver_id')->nullable()->constrained('users')->onDelete('set null');
        $table->enum('status', ['pending', 'confirmed', 'preparing', 'ready', 'in_transit', 'delivered', 'cancelled'])->default('pending');
        $table->enum('delivery_type', ['delivery', 'pickup'])->default('delivery');
        $table->enum('payment_method', ['gcash', 'cod'])->default('cod');
        $table->decimal('subtotal', 10, 2);
        $table->decimal('delivery_fee', 10, 2)->default(0);
        $table->decimal('tip', 10, 2)->default(0);
        $table->decimal('total', 10, 2);
        $table->decimal('total_weight_kg', 5, 2)->default(0);
        $table->text('delivery_address')->nullable();
        $table->string('delivery_note')->nullable();
        $table->timestamp('delivered_at')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
