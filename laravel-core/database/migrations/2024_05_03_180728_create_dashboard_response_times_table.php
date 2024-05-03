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
        Schema::create('dashboard_response_times', function (Blueprint $table) {
            $table->id();
            $table->timestamp('minute');
            $table->unsignedBigInteger('page')->nullable();
            $table->foreign('page')->references('id')->on('facebook_pages');
            $table->string('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dashboard_response_times');
    }
};
