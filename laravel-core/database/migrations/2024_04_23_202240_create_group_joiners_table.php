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
            $table->integer('max_join')->nullable();
            $table->integer('join');
            $table->integer('each');
            $table->string('start_at')->nullable();
            $table->string('end_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
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
