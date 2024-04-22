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
        Schema::create('facebook_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_id')->nullable()->index();
            $table->unsignedBigInteger('category');
            $table->foreign('category')->references('id')->on('facebook_categories');
            $table->string('name')->nullable();
            $table->string('username')->index();
            $table->string('email_pwd')->nullable();
            $table->string('pwd');
            $table->boolean('marketplace_at')->default(false);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facebook_accounts');
    }
};
