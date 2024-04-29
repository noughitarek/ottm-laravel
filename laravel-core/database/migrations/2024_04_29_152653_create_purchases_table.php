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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->integer('total_amount');
            $table->unsignedBigInteger('products_funding')->nullable();
            $table->foreign('products_funding')->references('id')->on('fundings');
            $table->unsignedBigInteger('tests_funding')->nullable();
            $table->foreign('tests_funding')->references('id')->on('fundings');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
