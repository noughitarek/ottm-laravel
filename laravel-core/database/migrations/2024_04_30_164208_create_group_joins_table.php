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
        Schema::create('group_joins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('joiner');
            $table->foreign('joiner')->references('id')->on('group_joiners');
            $table->unsignedBigInteger('account');
            $table->foreign('account')->references('id')->on('facebook_accounts');
            $table->string('facebook_group_id')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_joins');
    }
};
