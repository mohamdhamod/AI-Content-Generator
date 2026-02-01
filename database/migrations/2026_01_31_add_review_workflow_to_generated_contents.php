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
            // Review workflow fields
            $table->enum('review_status', [
                'draft', 
                'pending_review', 
                'reviewed', 
                'approved', 
                'rejected'
            ])->default('draft')->after('status');
            
            $table->foreignId('reviewed_by')->nullable()->after('review_status')
                  ->constrained('users')->nullOnDelete();
            
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            
            $table->text('review_notes')->nullable()->after('reviewed_at');
            
            // Version control
            $table->integer('version')->default(1)->after('review_notes');
            $table->foreignId('parent_content_id')->nullable()->after('version')
                  ->constrained('generated_contents')->nullOnDelete();
            
            // Publishing controls
            $table->boolean('is_published')->default(false)->after('parent_content_id');
            $table->timestamp('published_at')->nullable()->after('is_published');
            
            // Usage tracking
            $table->integer('view_count')->default(0)->after('published_at');
            $table->integer('share_count')->default(0)->after('view_count');
            $table->integer('pdf_download_count')->default(0)->after('share_count');
            
            // Indexes for performance
            $table->index('review_status');
            $table->index('reviewed_by');
            $table->index('is_published');
            $table->index(['user_id', 'review_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('generated_contents', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropForeign(['parent_content_id']);
            $table->dropIndex(['review_status']);
            $table->dropIndex(['reviewed_by']);
            $table->dropIndex(['is_published']);
            $table->dropIndex(['user_id', 'review_status']);
            
            $table->dropColumn([
                'review_status',
                'reviewed_by',
                'reviewed_at',
                'review_notes',
                'version',
                'parent_content_id',
                'is_published',
                'published_at',
                'view_count',
                'share_count',
                'pdf_download_count',
            ]);
        });
    }
};
