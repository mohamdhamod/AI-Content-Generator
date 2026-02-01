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
        Schema::create('generated_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('specialty_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('content_type_id')->constrained()->cascadeOnDelete();
            $table->json('input_data'); // User's input (topic, language, etc.)
            $table->longText('output_text'); // Generated content
            $table->string('language', 10)->default('en');
            $table->string('country', 50)->nullable();
            $table->integer('word_count')->default(0);
            $table->integer('credits_used')->default(1);
            $table->integer('tokens_used')->default(0);
            $table->enum('status', ['completed', 'failed', 'filtered'])->default('completed');
            $table->text('error_message')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index(['user_id', 'created_at']);
            $table->index(['specialty_id', 'content_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generated_contents');
    }
};
