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
                if (!Schema::hasColumn('remarketings', 'start_time')) {
                    $table->string('start_time')->nullable();
                }
                if (!Schema::hasColumn('remarketings', 'end_time')) {
                    $table->string('end_time')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('remarketings')) {
            Schema::table('remarketings', function (Blueprint $table) {
                
                if (Schema::hasColumn('remarketings', 'start_time')) {
                    $table->dropColumn('start_time');
                }
                if (Schema::hasColumn('remarketings', 'end_time')) {
                    $table->dropColumn('end_time');
                }
            });
        }
    }
};
