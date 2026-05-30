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

            // 購入者
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // 購入商品
            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            // 支払方法
            $table->string('payment_method');

            // 配送先
            $table->string('postal_code');
            $table->string('address');
            $table->string('building')->nullable();

            // 購入時価格
            $table->integer('price');

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
