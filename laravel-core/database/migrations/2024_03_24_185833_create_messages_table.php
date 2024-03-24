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
        Schema::create('messages', function (Blueprint $table) {
            $table->string('message_id')->primary();
            $table->string('sented_by')->nullable();
            $table->foreign('sented_by')->references('facebook_user_id')->on('facebook_users');
            $table->string('sented_to')->nullable();
            $table->foreign('sented_to')->references('facebook_user_id')->on('facebook_users');
            $table->TEXT('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
