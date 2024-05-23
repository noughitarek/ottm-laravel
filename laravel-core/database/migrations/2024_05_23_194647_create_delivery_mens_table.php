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
        Schema::create('delivery_mens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('desk')->nullable();
            $table->foreign('desk')->references('id')->on('desks');
            $table->unsignedBigInteger('commune')->nullable();
            $table->foreign('commune')->references('id')->on('communes');
            $table->string('phone_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_mens');
    }
};
