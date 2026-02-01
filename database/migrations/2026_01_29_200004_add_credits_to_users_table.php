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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('default_specialty_id')->nullable()->after('email')->constrained('specialties')->nullOnDelete();
            $table->integer('monthly_credits')->default(5)->after('email');
            $table->integer('used_credits')->default(0)->after('monthly_credits');
            $table->timestamp('credits_reset_at')->nullable()->after('used_credits');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['default_specialty_id']);
            $table->dropColumn(['default_specialty_id', 'monthly_credits', 'used_credits', 'credits_reset_at']);
        });
    }
};
