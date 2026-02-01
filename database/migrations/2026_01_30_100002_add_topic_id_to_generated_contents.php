<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds topic_id to generated_contents for tracking which topic was used.
     */
    public function up(): void
    {
        Schema::table('generated_contents', function (Blueprint $table) {
            $table->foreignId('topic_id')->nullable()->after('specialty_id')
                  ->constrained('topics')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('generated_contents', function (Blueprint $table) {
            $table->dropForeign(['topic_id']);
            $table->dropColumn('topic_id');
        });
    }
};
