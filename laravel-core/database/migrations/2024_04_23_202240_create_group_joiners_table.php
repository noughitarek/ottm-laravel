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
        Schema::create('group_joiners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('keywords');
            $table->unsignedBigInteger('category');
            $table->foreign('category')->references('id')->on('facebook_categories');
            $table->integer('max_join')->nullable();
            $table->integer('join');
            $table->integer('each');
            $talbe->integer('start_at');
            $talbe->integer('end_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_joiners');
    }
};
