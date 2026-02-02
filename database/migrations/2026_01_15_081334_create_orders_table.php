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
            $table->string('order_no')->unique();
            $table->bigInteger('user_id');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discounted_price', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('shipping_amount', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2);
            $table->enum('payment_status', ['pending','paid','failed'])->default('pending');
            $table->enum('order_status', ['pending','processing','completed','cancelled'])->default('panding');
            $table->enum('payment_method', ['cod','razorpay'])->default('cod');
            $table->text('notes')->nullable();
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
