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
            $table->integer('quantity')->default(1);
            $table->integer('total_price');
            $table->integer('delivery_price');
            $table->integer('clean_price');
            $table->string('tracking')->nullable();
            $table->string('intern_tracking')->unique();
            $table->string('IP')->nullable();
            $table->boolean('fragile')->default(0);
            $table->boolean('stopdesk')->default(0);
            $table->unsignedBigInteger('product');
            $table->foreign('product')->references('id')->on('products');
            $table->unsignedBigInteger('desk')->nullable();
            $table->foreign('desk')->references('id')->on('desks');
            $table->string('conversation')->nullable();
            $table->foreign('conversation')->references('id')->on('facebook_conversations');
            $table->timestamp('validated_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('wilaya_at')->nullable();
            $table->timestamp('delivery_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('ready_at')->nullable();
            $table->timestamp('recovered_at')->nullable();
            $table->timestamp('back_at')->nullable();
            $table->timestamp('back_ready_at')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
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
