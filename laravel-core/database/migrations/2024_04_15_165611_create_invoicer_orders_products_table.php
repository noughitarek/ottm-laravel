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
        Schema::create('invoicer_orders_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order')->nullable();
            $table->foreign('order')->references('id')->on('invoicer_orders');
            $table->unsignedBigInteger('product');
            $table->foreign('product')->references('id')->on('invoicer_products');
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoicer_orders_products');
    }
};
