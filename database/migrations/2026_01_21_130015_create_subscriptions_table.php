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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->decimal('price', 10, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->integer('max_content_generations')->default(10);
            $table->integer('duration_months')->default(1);
            $table->string('digistore_product_id')->nullable();
            $table->string('digistore_checkout_url')->nullable();

                    $table->json('features')->nullable();

            
                            $table->integer('sort_order')->default(0);

            $table->boolean('active')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
