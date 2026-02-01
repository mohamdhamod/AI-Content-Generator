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
        Schema::create('subscription_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained('subscriptions')->onDelete('cascade');
            $table->string('icon')->nullable();
            $table->boolean('is_highlighted')->default(false);
            $table->integer('sort_order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('sub_feature_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscription_feature_id');
            $table->string('locale')->index();
            $table->string('feature_text');
            $table->unique(['subscription_feature_id', 'locale'], 'sub_feat_trans_unique');
            $table->timestamps();

            $table->foreign('subscription_feature_id', 'sub_feat_trans_fk')
                ->references('id')
                ->on('subscription_features')
                ->onDelete('cascade');
        });

        // Remove features JSON column from subscriptions table if exists
        if (Schema::hasColumn('subscriptions', 'features')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->dropColumn('features');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_feature_translations');
        Schema::dropIfExists('subscription_features');

        // Add features column back to subscriptions
        if (!Schema::hasColumn('subscriptions', 'features')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->json('features')->nullable();
            });
        }
    }
};
