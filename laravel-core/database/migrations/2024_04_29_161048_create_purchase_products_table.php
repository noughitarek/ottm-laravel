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
        Schema::create('purchase_products', function (Blueprint $table) {
            $table->id();
            $table->integer('purchase_price');
            $table->unsignedBigInteger('purchase');
            $table->foreign('purchase')->references('id')->on('purchases');
            $table->unsignedBigInteger('product');
            $table->foreign('product')->references('id')->on('products');
            $table->unsignedBigInteger('desk')->nullable();
            $table->foreign('desk')->references('id')->on('desks');
            $table->string('state')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_products');
    }
};