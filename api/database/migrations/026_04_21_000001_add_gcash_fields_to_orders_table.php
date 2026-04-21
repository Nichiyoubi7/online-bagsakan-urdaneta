<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('gcash_receipt')->nullable()->after('payment_status');
            $table->string('gcash_ref_no')->nullable()->after('gcash_receipt');
            $table->boolean('payment_verified')->default(false)->after('gcash_ref_no');
            $table->timestamp('payment_verified_at')->nullable()->after('payment_verified');
            $table->text('ai_verification_result')->nullable()->after('payment_verified_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['gcash_receipt', 'gcash_ref_no', 'payment_verified', 'payment_verified_at', 'ai_verification_result']);
        });
    }
};