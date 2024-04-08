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
        if (Schema::hasTable('remarketings')) {
            Schema::table('remarketings', function (Blueprint $table) {
                if (!Schema::hasColumn('remarketings', 'audios')) {
                    $table->string('audios')->nullable();
                }
            });
        }
        if (Schema::hasTable('messages_templates')) {
            Schema::table('messages_templates', function (Blueprint $table) {
                if (!Schema::hasColumn('messages_templates', 'audios')) {
                    $table->string('audios')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
