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
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('specialty_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('content_type_id')->nullable()->constrained()->nullOnDelete();
            
            // Template info
            $table->string('name');
            $table->text('description')->nullable();
            $table->longText('template_content');
            
            // Variables/placeholders in template
            $table->json('variables')->nullable(); // [{name: 'patient_name', type: 'text', required: true}]
            
            // Template settings
            $table->string('language')->default('en');
            $table->enum('visibility', ['private', 'team', 'public'])->default('private');
            $table->boolean('is_active')->default(true);
            
            // Usage tracking
            $table->integer('usage_count')->default(0);
            $table->timestamp('last_used_at')->nullable();
            
            // Versioning
            $table->integer('version')->default(1);
            $table->foreignId('parent_template_id')->nullable()->constrained('templates')->nullOnDelete();
            
            // Sharing & collaboration
            $table->foreignId('team_workspace_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('allow_team_edit')->default(false);
            
            // Metadata
            $table->json('metadata')->nullable(); // Tags, category, rating, etc.
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('user_id');
            $table->index('visibility');
            $table->index(['user_id', 'is_active']);
            $table->index('team_workspace_id');
            $table->fullText(['name', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
