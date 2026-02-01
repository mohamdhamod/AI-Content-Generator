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
        Schema::create('content_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('generated_content_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Action tracking
            $table->enum('action_type', [
                'view',
                'pdf_export',
                'pdf_download',
                'social_preview',
                'copy_content',
                'favorite',
                'unfavorite',
                'share',
                'edit',
                'submit_for_review',
                'approve',
                'reject',
                'ai_refine',
                'tone_adjust',
                'version_create',
                'version_compare',
                'version_restore',
                // Phase 3 actions
                'seo_check',
                'schedule_publish',
                'reschedule',
                'publish',
                'archive'
            ]);
            
            // Platform details (for social preview)
            $table->string('platform')->nullable(); // facebook, twitter, linkedin, instagram
            
            // Device info
            $table->string('device_type')->nullable(); // desktop, mobile, tablet
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            
            // Location (if available)
            $table->string('country_code', 2)->nullable();
            $table->string('city')->nullable();
            
            // Additional metadata
            $table->json('metadata')->nullable(); // flexible for extra data
            
            $table->ipAddress('ip_address')->nullable();
            $table->timestamp('created_at');
            
            // Indexes for analytics queries
            $table->index('action_type');
            $table->index(['user_id', 'action_type']);
            $table->index(['generated_content_id', 'action_type']);
            $table->index('created_at');
            $table->index(['action_type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_analytics');
    }
};
