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
        Schema::create('team_workspaces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            
            // Workspace info
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            
            // Settings
            $table->json('settings')->nullable(); // Permissions, features, limits
            $table->boolean('is_active')->default(true);
            
            // Subscription/billing
            $table->enum('plan', ['free', 'team', 'enterprise'])->default('free');
            $table->integer('member_limit')->default(5);
            $table->integer('storage_limit_mb')->default(1000);
            
            // Statistics
            $table->integer('member_count')->default(1);
            $table->integer('content_count')->default(0);
            $table->integer('template_count')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('owner_id');
            $table->index('slug');
            $table->index('is_active');
        });
        
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_workspace_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invited_by')->nullable()->constrained('users')->nullOnDelete();
            
            // Role & permissions
            $table->enum('role', ['owner', 'admin', 'editor', 'reviewer', 'viewer'])->default('viewer');
            $table->json('permissions')->nullable(); // Custom permissions
            
            // Status
            $table->enum('status', ['pending', 'active', 'suspended'])->default('pending');
            $table->timestamp('invited_at')->nullable();
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('last_active_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->unique(['team_workspace_id', 'user_id']);
            $table->index('user_id');
            $table->index('role');
            $table->index('status');
        });
        
        Schema::create('content_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('generated_content_id')->constrained()->cascadeOnDelete();
            $table->foreignId('team_workspace_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_by')->constrained('users')->cascadeOnDelete();
            
            // Assignment details
            $table->text('notes')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            
            $table->timestamps();
            
            // Indexes
            $table->index('assigned_to');
            $table->index('status');
            $table->index(['team_workspace_id', 'status']);
        });
        
        Schema::create('content_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('generated_content_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_comment_id')->nullable()->constrained('content_comments')->cascadeOnDelete();
            
            // Comment content
            $table->text('comment');
            $table->json('mentions')->nullable(); // [@user_id]
            
            // Annotations (for specific text selection)
            $table->string('annotation_start')->nullable(); // Text position
            $table->string('annotation_end')->nullable();
            $table->text('annotated_text')->nullable();
            
            // Status
            $table->boolean('is_resolved')->default(false);
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('generated_content_id');
            $table->index('user_id');
            $table->index('is_resolved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_comments');
        Schema::dropIfExists('content_assignments');
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('team_workspaces');
    }
};
