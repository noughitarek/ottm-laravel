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
            $table->string('name');
            $table->string('phone');
            $table->string('phone2')->nullable();
            $table->string('address');
            $table->unsignedBigInteger('commune');
            $table->foreign('commune')->references('id')->on('communes');
            $table->unsignedBigInteger('wilaya');
            $table->foreign('wilaya')->references('id')->on('wilayas');
            $table->integer('quantity');
            $table->double('total_price');
            $table->double('delivery_price');
            $table->double('clean_price');
            $table->string('tracking')->nullable();
            $table->string('ip');
            $table->boolean('stopdesk')->default(false);
            $table->boolean('damaged')->default(false);



            $table->timestamp('recovered_at')->nullable();
            $table->unsignedBigInteger('recovered_by')->nullable();
            $table->foreign('recovered_by')->references('id')->on('users');
            
            

            $table->unsignedBigInteger('product')->nullable();
            $table->foreign('product')->references('id')->on('products');

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
