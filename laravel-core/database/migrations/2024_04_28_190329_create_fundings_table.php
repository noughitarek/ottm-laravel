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
        Schema::create('fundings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('total_amount');
            $table->integer('type');
            $table->unsignedTinyInteger('investor_pourcentage')->default(50);
            $table->unsignedBigInteger('investor');
            $table->foreign('investor')->references('id')->on('investors');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fundings');
    }
};
