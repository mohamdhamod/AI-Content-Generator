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
        Schema::table('content_types', function (Blueprint $table) {
            $table->text('prompt_requirements')->nullable()->after('credits_cost');
            $table->integer('min_word_count')->default(500)->after('prompt_requirements');
            $table->integer('max_word_count')->default(1200)->after('min_word_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('content_types', function (Blueprint $table) {
            $table->dropColumn(['prompt_requirements', 'min_word_count', 'max_word_count']);
        });
    }
};
