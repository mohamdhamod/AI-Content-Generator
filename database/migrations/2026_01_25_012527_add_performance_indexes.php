<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * These indexes improve query performance for:
     * - Product filtering by status (N+1 queries fix)
     * - Store ownership lookups
     * - Category-Product relationship queries
     * - Store subscription filtering
     * - Delivery driver status filtering
     */
    public function up(): void
    {
        // Index for products table - status filtering is very common
        if (Schema::hasTable('products') && !$this->hasIndex('products', 'products_status_index')) {
            Schema::table('products', function (Blueprint $table) {
                $table->index('status', 'products_status_index');
            });
        }

        // Index for products table - user_id for ownership queries
        if (Schema::hasTable('products') && !$this->hasIndex('products', 'products_user_id_index')) {
            Schema::table('products', function (Blueprint $table) {
                $table->index('user_id', 'products_user_id_index');
            });
        }

        // Composite index for category_products pivot table
        if (Schema::hasTable('category_products') && !$this->hasIndex('category_products', 'category_products_composite_index')) {
            Schema::table('category_products', function (Blueprint $table) {
                $table->index(['category_id', 'product_id'], 'category_products_composite_index');
            });
        }

        // Index for store_subscriptions table - common filters
        if (Schema::hasTable('store_subscriptions')) {
            if (!$this->hasIndex('store_subscriptions', 'store_subscriptions_store_id_index')) {
                Schema::table('store_subscriptions', function (Blueprint $table) {
                    $table->index('store_id', 'store_subscriptions_store_id_index');
                });
            }
            if (!$this->hasIndex('store_subscriptions', 'store_subscriptions_payment_status_index')) {
                Schema::table('store_subscriptions', function (Blueprint $table) {
                    $table->index('payment_status', 'store_subscriptions_payment_status_index');
                });
            }
            if (!$this->hasIndex('store_subscriptions', 'store_subscriptions_expiry_date_index')) {
                Schema::table('store_subscriptions', function (Blueprint $table) {
                    $table->index('expiry_date', 'store_subscriptions_expiry_date_index');
                });
            }
        }

        // Index for delivery_drivers table - status filtering
        if (Schema::hasTable('delivery_drivers') && !$this->hasIndex('delivery_drivers', 'delivery_drivers_status_index')) {
            Schema::table('delivery_drivers', function (Blueprint $table) {
                $table->index('status', 'delivery_drivers_status_index');
            });
        }

        // Index for stores table - user_id for ownership queries
        if (Schema::hasTable('stores') && !$this->hasIndex('stores', 'stores_user_id_index')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->index('user_id', 'stores_user_id_index');
            });
        }

        // Index for carts table - common lookups
        if (Schema::hasTable('carts')) {
            if (!$this->hasIndex('carts', 'carts_user_id_status_index')) {
                Schema::table('carts', function (Blueprint $table) {
                    $table->index(['user_id', 'status'], 'carts_user_id_status_index');
                });
            }
            if (!$this->hasIndex('carts', 'carts_token_index')) {
                Schema::table('carts', function (Blueprint $table) {
                    $table->index('token', 'carts_token_index');
                });
            }
        }

        // Index for cart_items table - cart relationship
        if (Schema::hasTable('cart_items') && !$this->hasIndex('cart_items', 'cart_items_cart_id_index')) {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->index('cart_id', 'cart_items_cart_id_index');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Products indexes
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropIndex('products_status_index');
                $table->dropIndex('products_user_id_index');
            });
        }

        // Category products index
        if (Schema::hasTable('category_products')) {
            Schema::table('category_products', function (Blueprint $table) {
                $table->dropIndex('category_products_composite_index');
            });
        }

        // Store subscriptions indexes
        if (Schema::hasTable('store_subscriptions')) {
            Schema::table('store_subscriptions', function (Blueprint $table) {
                $table->dropIndex('store_subscriptions_store_id_index');
                $table->dropIndex('store_subscriptions_payment_status_index');
                $table->dropIndex('store_subscriptions_expiry_date_index');
            });
        }

        // Delivery drivers index
        if (Schema::hasTable('delivery_drivers')) {
            Schema::table('delivery_drivers', function (Blueprint $table) {
                $table->dropIndex('delivery_drivers_status_index');
            });
        }

        // Stores index
        if (Schema::hasTable('stores')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->dropIndex('stores_user_id_index');
            });
        }

        // Carts indexes
        if (Schema::hasTable('carts')) {
            Schema::table('carts', function (Blueprint $table) {
                $table->dropIndex('carts_user_id_status_index');
                $table->dropIndex('carts_token_index');
            });
        }

        // Cart items index
        if (Schema::hasTable('cart_items')) {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->dropIndex('cart_items_cart_id_index');
            });
        }
    }

    /**
     * Check if an index exists on a table.
     */
    private function hasIndex(string $table, string $indexName): bool
    {
        $connection = Schema::getConnection();
        $databaseName = $connection->getDatabaseName();
        
        $result = $connection->selectOne(
            "SELECT COUNT(*) as count FROM information_schema.statistics 
             WHERE table_schema = ? AND table_name = ? AND index_name = ?",
            [$databaseName, $table, $indexName]
        );
        
        return $result->count > 0;
    }
};
