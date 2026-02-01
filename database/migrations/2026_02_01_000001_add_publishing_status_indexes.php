<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds indexes for publishing_status and scheduled_at for better calendar/schedule query performance
     */
    public function up(): void
    {
        Schema::table('generated_contents', function (Blueprint $table) {
            // Check if index doesn't exist before adding
            if (!$this->hasIndex('generated_contents', 'generated_contents_publishing_status_index')) {
                $table->index('publishing_status', 'generated_contents_publishing_status_index');
            }
            
            if (!$this->hasIndex('generated_contents', 'generated_contents_publishing_scheduled_index')) {
                $table->index(['publishing_status', 'scheduled_at'], 'generated_contents_publishing_scheduled_index');
            }
            
            if (!$this->hasIndex('generated_contents', 'generated_contents_scheduled_at_index')) {
                $table->index('scheduled_at', 'generated_contents_scheduled_at_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('generated_contents', function (Blueprint $table) {
            $table->dropIndex('generated_contents_publishing_status_index');
            $table->dropIndex('generated_contents_publishing_scheduled_index');
            $table->dropIndex('generated_contents_scheduled_at_index');
        });
    }
    
    /**
     * Check if an index exists on a table
     */
    private function hasIndex(string $table, string $indexName): bool
    {
        $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);
        return count($indexes) > 0;
    }
};
