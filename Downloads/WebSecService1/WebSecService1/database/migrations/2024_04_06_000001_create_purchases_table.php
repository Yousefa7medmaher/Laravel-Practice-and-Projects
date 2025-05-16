<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('total_price', 10, 2);
            $table->string('delivery_method');
            $table->string('delivery_address')->nullable();
            $table->string('pickup_branch')->nullable();
            $table->string('color');
            $table->string('payment_method');
            $table->string('card_last4')->nullable();
            $table->string('order_status')->default('Pending');
            $table->timestamp('estimated_delivery_time')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}; 