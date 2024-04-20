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
            Schema::table('remarketing_interval_messages', function($table) {
                $table->index('facebook_conversation_id');
            });
        }
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('remarketing_interval_messages')) {
            Schema::table('remarketing_interval_messages', function($table) {
                $table->dropIndex('remarketing_interval_messages_facebook_conversation_id_index');
            });
        }
    }
};
