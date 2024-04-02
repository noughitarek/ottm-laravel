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
        Schema::create('remarketing_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('remarketing')->nullable();
            $table->foreign('remarketing')->references('id')->on('remarketings');
            $table->string('facebook_conversation_id');
            $table->timestamp('last_use');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remarketing_messages');
    }
};
