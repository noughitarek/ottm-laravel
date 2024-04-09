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
        Schema::create('remarketing_intervals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('facebook_page_id');
            $table->integer('start_after');
            $table->integer('send_after_each');
            $table->integer('devide_by');
            $table->unsignedBigInteger('template');
            $table->foreign('template')->references('id')->on('messages_templates');
            $table->boolean('is_active')->default(false);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remarketing_intervals');
    }
};
