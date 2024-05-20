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
                if (!Schema::hasColumn('remarketings', 'resend_after')) {
                    $table->string('resend_after')->nullable();
                }
            });
        }
        if (Schema::hasTable('remarketing_messages')) {
            Schema::table('remarketing_messages', function (Blueprint $table) {
                if (!Schema::hasColumn('remarketing_messages', 'expire_at')) {
                    $table->string('expire_at')->nullable();
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
