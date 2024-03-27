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
        Schema::create('facebook_conversations', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('page');
            $table->string('user');
            $table->boolean('can_reply');
            $table->foreign('page')->references('id')->on('facebook_pages');
            $table->foreign('user')->references('id')->on('facebook_users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facebook_conversations');
    }
};
