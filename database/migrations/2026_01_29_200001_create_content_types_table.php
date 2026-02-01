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
        Schema::create('content_types', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->index();
            $table->string('key')->unique(); // patient_education, seo_blog, social_media, etc.
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->integer('credits_cost')->default(1); // Credits per generation
            $table->timestamps();
        });

        Schema::create('content_type_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_type_id')->constrained()->cascadeOnDelete();
            $table->string('locale')->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('placeholder')->nullable(); // Hint text for input
            $table->unique(['content_type_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_type_translations');
        Schema::dropIfExists('content_types');
    }
};
