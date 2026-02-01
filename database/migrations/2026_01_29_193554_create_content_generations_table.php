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
        Schema::create('content_generations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_subscription_id')->nullable()->constrained()->onDelete('set null');
            $table->string('content_type'); // patient_education, seo_blog_article, etc.
            $table->string('specialty')->nullable(); // dentistry, dermatology, etc.
            $table->string('language', 10)->default('en');
            $table->string('topic')->nullable();
            $table->text('prompt')->nullable();
            $table->longText('generated_content')->nullable();
            $table->integer('tokens_used')->default(0);
            $table->string('model_used')->nullable(); // gpt-4, gpt-3.5-turbo, etc.
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->json('metadata')->nullable(); // Additional data like generation time, etc.
            $table->timestamps();

            // Indexes for faster queries
            $table->index(['user_id', 'created_at']);
            $table->index(['user_id', 'status']);
            $table->index('content_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_generations');
    }
};
