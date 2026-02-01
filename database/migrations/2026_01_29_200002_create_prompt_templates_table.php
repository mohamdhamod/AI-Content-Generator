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
        Schema::create('prompt_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('specialty_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('content_type_id')->constrained()->cascadeOnDelete();
            $table->string('locale')->default('en');
            $table->text('system_prompt'); // The main system prompt
            $table->text('user_prompt_template'); // Template with {placeholders}
            $table->json('required_fields')->nullable(); // Fields needed for this template
            $table->json('optional_fields')->nullable(); // Optional fields
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->unique(['specialty_id', 'content_type_id', 'locale'], 'prompt_template_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prompt_templates');
    }
};
