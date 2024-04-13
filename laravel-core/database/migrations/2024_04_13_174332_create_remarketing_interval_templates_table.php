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
        Schema::table('remarketing_intervals', function (Blueprint $table) {
            if (Schema::hasColumn('remarketing_intervals', 'template')) {
                #$table->dropForeign('remarketing_intervals_template_foreign');
                $table->dropColumn('template');
            }
        });
        Schema::create('remarketing_interval_templates', function (Blueprint $table) {
            $table->id();
            $table->integer('order');
            $table->unsignedBigInteger('remarketing');
            $table->foreign('remarketing')->references('id')->on('remarketing_intervals');
            $table->unsignedBigInteger('template');
            $table->foreign('template')->references('id')->on('messages_templates');
            $table->boolean('used')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('remarketing_intervals', function (Blueprint $table) {
            if (!Schema::hasColumn('remarketing_intervals', 'template')) {
                $table->unsignedBigInteger('template')->nullable();
                $table->foreign('template')->references('id')->on('templates');
            }
        });
        Schema::dropIfExists('remarketing_interval_templates');
    }
};
