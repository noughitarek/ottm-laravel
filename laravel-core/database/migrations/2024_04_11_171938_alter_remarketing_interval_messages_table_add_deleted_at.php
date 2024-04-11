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
        if (Schema::hasTable('remarketing_interval_messages')) {
            Schema::table('remarketing_interval_messages', function (Blueprint $table) {
                if (!Schema::hasColumn('remarketing_interval_messages', 'deleted_at')) {
                    $table->timestamp('deleted_at')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('remarketing_interval_messages')) {
            Schema::table('remarketing_interval_messages', function (Blueprint $table) {
                if (Schema::hasColumn('remarketing_interval_messages', 'deleted_at')) {
                    $table->dropColumn('deleted_at');
                }
            });
        }
    }
};
