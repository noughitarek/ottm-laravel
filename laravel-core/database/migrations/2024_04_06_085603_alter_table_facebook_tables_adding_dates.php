<?php

use App\Models\FacebookUser;
use Illuminate\Support\Facades\DB;
use App\Models\FacebookConversation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('facebook_conversations')) {
            Schema::table('facebook_conversations', function (Blueprint $table) {
                if (!Schema::hasColumn('facebook_conversations', 'started_at')) {
                    $table->timestamp('started_at')->nullable();
                }
                if (!Schema::hasColumn('facebook_conversations', 'ended_at')) {
                    $table->timestamp('ended_at')->nullable();
                }
                if (!Schema::hasColumn('facebook_conversations', 'last_from_user_at')) {
                    $table->timestamp('last_from_user_at')->nullable();
                }
                if (!Schema::hasColumn('facebook_conversations', 'last_from_page_at')) {
                    $table->timestamp('last_from_page_at')->nullable();
                }
                
                if (!Schema::hasColumn('facebook_conversations', 'last_from')) {
                    $table->string('last_from')->nullable();
                }
                if (!Schema::hasColumn('facebook_conversations', 'make_order')) {
                    $table->boolean('make_order')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('facebook_conversations')) {
            Schema::table('facebook_conversations', function (Blueprint $table) {
                
                if (Schema::hasColumn('facebook_conversations', 'last_from')) {
                    $table->dropColumn('last_from');
                }
                if (Schema::hasColumn('facebook_conversations', 'make_order')) {
                    $table->dropColumn('make_order');
                }
                if (Schema::hasColumn('facebook_conversations', 'last_from_page_at')) {
                    $table->dropColumn('last_from_page_at');
                }
                if (Schema::hasColumn('facebook_conversations', 'started_at')) {
                    $table->dropColumn('started_at');
                }
                if (Schema::hasColumn('facebook_conversations', 'ended_at')) {
                    $table->dropColumn('ended_at');
                }
                if (Schema::hasColumn('facebook_conversations', 'last_from_user_at')) {
                    $table->dropColumn('last_from_user_at');
                }
            });
        }
    }
};
