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
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('specialty_id')->constrained('specialties')->onDelete('cascade');
            $table->string('icon')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('topic_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained('topics')->onDelete('cascade');
            $table->string('locale')->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unique(['topic_id', 'locale']);
            $table->timestamps();
        });

        // Remove topics column from specialties table if exists
        if (Schema::hasColumn('specialties', 'topics')) {
            Schema::table('specialties', function (Blueprint $table) {
                $table->dropColumn('topics');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topic_translations');
        Schema::dropIfExists('topics');

        // Add topics column back to specialties
        Schema::table('specialties', function (Blueprint $table) {
            $table->json('topics')->nullable();
        });
    }
};
