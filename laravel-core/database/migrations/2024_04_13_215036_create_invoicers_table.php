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
        Schema::create('invoicers', function (Blueprint $table) {
            $table->id();
            $table->integer("total_amount")->nullable();
            $table->integer("total_orders")->nullable();
            $table->unsignedBigInteger('desk')->nullable();
            $table->foreign('desk')->references('id')->on('desks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoicers');
    }
};
