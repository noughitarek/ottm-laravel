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
        Schema::create('group_joiners_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_joiner');
            $table->foreign('group_joiner')->references('id')->on('group_joiners');
            $table->unsignedBigInteger('category');
            $table->foreign('category')->references('id')->on('facebook_categories');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_joiners_categories');
    }
};
