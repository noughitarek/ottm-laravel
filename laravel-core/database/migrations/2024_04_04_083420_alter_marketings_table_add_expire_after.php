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
                if (!Schema::hasColumn('remarketings', 'is_active')) {
                    $table->boolean('is_active')->default(false);
                }
                if (!Schema::hasColumn('remarketings', 'expire_after')) {
                    $table->integer('expire_after')->nullable();
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
                if (Schema::hasColumn('remarketings', 'is_active')) {
                    $table->dropColumn('is_active');
                }
                if (Schema::hasColumn('remarketings', 'expire_after')) {
                    $table->dropColumn('expire_after');
                }
            });
        }
    }
};
