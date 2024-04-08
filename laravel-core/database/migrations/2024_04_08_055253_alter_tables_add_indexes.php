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
        if (Schema::hasTable('remarketing_messages')) {
            Schema::table('remarketing_messages', function($table) {
                $table->index('facebook_conversation_id');
            });
        }
        if (Schema::hasTable('remarketings')) {
            Schema::table('remarketings', function($table) {
                $table->index('facebook_page_id');
            });
        }
        if (Schema::hasTable('facebook_users')) {
            Schema::table('facebook_users', function($table) {
                $table->index('facebook_user_id');
            });
        }
        if (Schema::hasTable('facebook_pages')) {
            Schema::table('facebook_pages', function($table) {
                $table->index('facebook_page_id');
            });
        }
        if (Schema::hasTable('facebook_conversations')) {
            Schema::table('facebook_conversations', function($table) {
                $table->index('facebook_conversation_id');
                $table->index('page'); 
                $table->index('user');
            });
        }
        if (Schema::hasTable('facebook_messages')) {
            Schema::table('facebook_messages', function($table) {
                $table->index('facebook_message_id');
                $table->index('conversation');
            });
        }
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('remarketing_messages')) {
            Schema::table('remarketing_messages', function($table) {
                $table->dropIndex('remarketing_messages_facebook_conversation_id_index');
            });
        }
        if (Schema::hasTable('remarketings')) {
            Schema::table('remarketings', function($table) {
                $table->dropIndex('remarketings_facebook_page_id_index');
            });
        }
        if (Schema::hasTable('facebook_users')) {
            Schema::table('facebook_users', function($table) {
                $table->dropIndex('facebook_users_facebook_user_id_index');
            });
        }
        if (Schema::hasTable('facebook_pages')) {
            Schema::table('facebook_pages', function($table) {
                $table->dropIndex('facebook_pages_facebook_page_id_index');
            });
        }
        if (Schema::hasTable('facebook_conversations')) {
            Schema::table('facebook_conversations', function($table) {
                $table->dropIndex('facebook_conversations_facebook_conversation_id_index'); 
                $table->dropIndex('facebook_conversations_page_index'); 
                $table->dropIndex('facebook_conversations_user_index');
            });
        }
        if (Schema::hasTable('facebook_messages')) {
            Schema::table('facebook_messages', function($table) {
                $table->dropIndex('facebook_messages_facebook_message_id_index'); 
                $table->dropIndex('facebook_messages_conversation_index'); 
            });
        }
    }
};
