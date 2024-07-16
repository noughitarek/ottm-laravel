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
        if (Schema::hasTable('desks')) {
            Schema::table('desks', function (Blueprint $table) {
                if (!Schema::hasColumn('desks', 'default_stock')) {
                    $table->boolean('default_stock')->default(0);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('desks')) {
            Schema::table('desks', function (Blueprint $table) {
                if (Schema::hasColumn('desks', 'default_stock')) {
                    $table->dropColumn('default_stock');
                }
            });
        }
    }
};
