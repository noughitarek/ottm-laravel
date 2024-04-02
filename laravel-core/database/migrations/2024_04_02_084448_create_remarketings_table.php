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
        Schema::create('remarketings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('facebook_page_id');
            $table->integer('send_after');
            $table->string('last_message_from')->default('any');
            $table->boolean('make_order')->default(false);
            $table->string('since')->default('conversation_start');
            $table->text('photos')->nullable();
            $table->text('video')->nullable();
            $table->text('message')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remarketings');
    }
};
