<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Update existing default from 0 to 5 for monthly_credits
            $table->integer('monthly_credits')->default(5)->change();
        });
        
        // Update existing users to have 5 free credits
        DB::table('users')
            ->where('monthly_credits', 0)
            ->update(['monthly_credits' => 5]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert to old default
            $table->integer('monthly_credits')->default(0)->change();
        });
    }
};
