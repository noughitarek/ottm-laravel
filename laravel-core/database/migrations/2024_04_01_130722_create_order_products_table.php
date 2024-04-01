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
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'product')) {
                $table->dropForeign('orders_product_foreign');
                $table->dropColumn('product');
            }
            if (Schema::hasColumn('orders', 'quantity')) {
                $table->dropColumn('quantity');
            }
        });
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order');
            $table->foreign('order')->references('id')->on('orders');
            $table->unsignedBigInteger('product');
            $table->foreign('product')->references('id')->on('products');
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('product')->nullable();
            $table->foreign('product')->references('id')->on('products');
            $table->integer('quantity')->default(1);
        });
        Schema::dropIfExists('order_products');
    }
};
