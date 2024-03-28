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
            $table->id();
            $table->string('facebook_conversation_id')->unique();
            $table->string('page')->nullable();
            $table->string('user')->nullable();
            $table->boolean('can_reply');
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
