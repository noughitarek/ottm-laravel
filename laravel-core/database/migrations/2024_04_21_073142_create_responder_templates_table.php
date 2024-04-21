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
        Schema::create('responder_templates', function (Blueprint $table) {
            $table->id();
            $table->integer('order');
            $table->unsignedBigInteger('responder');
            $table->foreign('responder')->references('id')->on('responders');
            $table->unsignedBigInteger('template');
            $table->foreign('template')->references('id')->on('messages_templates');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responder_templates');
    }
};
