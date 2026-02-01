<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds prompt_hint to topic_translations for AI context enhancement.
     * This allows each topic to have specific instructions that help
     * the AI generate more relevant and accurate content.
     */
    public function up(): void
    {
        Schema::table('topic_translations', function (Blueprint $table) {
            $table->text('prompt_hint')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('topic_translations', function (Blueprint $table) {
            $table->dropColumn('prompt_hint');
        });
    }
};
