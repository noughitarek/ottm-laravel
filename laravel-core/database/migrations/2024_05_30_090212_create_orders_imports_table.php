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
        Schema::create('orders_imports', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('phone');
            $table->string('phone2')->nullable();
            $table->unsignedBigInteger('commune');
            $table->foreign('commune')->references('id')->on('communes');
            $table->unsignedBigInteger('desk')->nullable();
            $table->foreign('desk')->references('id')->on('desks');
            $table->string('address')->nullable();
            $table->boolean('stopdesk')->default(0);
            $table->boolean('fragile')->default(0);
            $table->boolean('is_test')->default(0);
            $table->text('description')->nullable();
            $table->integer('total_price');
            $table->integer('delivery_price');
            $table->integer('clean_price');
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->string('IP')->nullable();
            $table->string('intern_tracking')->nullable();
            $table->boolean('from_stock')->default(1);
            $table->text('products')->nullable();
            $table->boolean('upload')->default(1);
            $table->boolean('validate')->default(1);
            $table->timestamp('validated_at')->nullable();
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders_imports');
    }
};
