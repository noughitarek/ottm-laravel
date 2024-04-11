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
        if (Schema::hasTable('remarketing_intervals')) {
            Schema::table('remarketing_intervals', function (Blueprint $table) {
                if (!Schema::hasColumn('remarketing_intervals', 'category')) {
                    $table->unsignedBigInteger('category')->default(5);
                    $table->foreign('category')->references('id')->on('remarketing_categories');
                }
            });
        }
        if (Schema::hasTable('remarketings')) {
            Schema::table('remarketings', function (Blueprint $table) {
                if (!Schema::hasColumn('remarketings', 'category')) {
                    $table->unsignedBigInteger('category')->default(5);
                    $table->foreign('category')->references('id')->on('remarketing_categories');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('remarketing_intervals')) {
            Schema::table('remarketing_intervals', function (Blueprint $table) {
                if (Schema::hasColumn('remarketing_intervals', 'category')) {
                    $table->dropForeign('remarketing_intervals_category_foreign');
                    $table->dropColumn('category');
                }
            });
        }
        if (Schema::hasTable('remarketings')) {
            Schema::table('remarketings', function (Blueprint $table) {
                if (Schema::hasColumn('remarketings', 'category')) {
                    $table->dropForeign('remarketings_category_foreign');
                    $table->dropColumn('category');
                }
            });
        }
    }
};
