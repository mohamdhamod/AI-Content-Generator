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
        // Add invitation_token and pending_email to team_members
        Schema::table('team_members', function (Blueprint $table) {
            $table->string('invitation_token', 64)->nullable()->unique()->after('status');
            $table->string('pending_email')->nullable()->after('invitation_token'); // For non-registered users
            $table->timestamp('invitation_expires_at')->nullable()->after('pending_email');
        });

        // Make user_id nullable for pending invitations to non-registered users
        Schema::table('team_members', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->dropColumn(['invitation_token', 'pending_email', 'invitation_expires_at']);
        });
    }
};
