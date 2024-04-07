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
        Schema::create('messages_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('product')->nullable();
            $table->foreign('product')->references('id')->on('products');
            $table->text('photos')->nullable();
            $table->text('video')->nullable();
            $table->text('message')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
        if (Schema::hasTable('remarketings')) {
            Schema::table('remarketings', function (Blueprint $table) {
                if (!Schema::hasColumn('remarketings', 'template')) {
                    $table->unsignedBigInteger('template')->nullable();
                    $table->foreign('template')->references('id')->on('messages_templates');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages_templates');
        if (Schema::hasTable('remarketings')) {
            Schema::table('remarketings', function (Blueprint $table) {
                if (Schema::hasColumn('remarketings', 'template')) {
                    $table->dropForeign('messages_templates_template_foreign');
                    $table->dropColumn('template');
                }
            });
        }
    }
};
