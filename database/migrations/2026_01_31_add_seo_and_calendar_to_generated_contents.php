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
        Schema::table('generated_contents', function (Blueprint $table) {
            // Publishing workflow fields
            $table->enum('publishing_status', [
                'draft',
                'scheduled',
                'published',
                'archived'
            ])->default('draft')->after('is_published');
            
            $table->timestamp('scheduled_at')->nullable()->after('published_at');
            $table->text('publishing_notes')->nullable()->after('scheduled_at');
            
            // SEO fields
            $table->string('seo_title')->nullable()->after('publishing_notes');
            $table->text('seo_meta_description')->nullable()->after('seo_title');
            $table->string('seo_focus_keyword')->nullable()->after('seo_meta_description');
            $table->json('seo_score_data')->nullable()->after('seo_focus_keyword');
            $table->integer('seo_overall_score')->nullable()->after('seo_score_data');
            
            // Publishing platform tracking
            $table->json('published_platforms')->nullable()->after('seo_overall_score');
            $table->timestamp('last_seo_check')->nullable()->after('published_platforms');
            
            // Indexes for calendar queries
            $table->index('publishing_status');
            $table->index('scheduled_at');
            $table->index(['user_id', 'publishing_status']);
            $table->index(['scheduled_at', 'publishing_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('generated_contents', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'publishing_status']);
            $table->dropIndex(['scheduled_at', 'publishing_status']);
            $table->dropIndex(['publishing_status']);
            $table->dropIndex(['scheduled_at']);
            
            $table->dropColumn([
                'publishing_status',
                'scheduled_at',
                'publishing_notes',
                'seo_title',
                'seo_meta_description',
                'seo_focus_keyword',
                'seo_score_data',
                'seo_overall_score',
                'published_platforms',
                'last_seo_check',
            ]);
        });
    }
};
