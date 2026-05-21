<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add indexes on frequently queried columns for speed optimization.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->index(['category', 'is_active'], 'products_category_is_active_index');
            $table->index('is_active', 'products_is_active_index');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index('status', 'orders_status_index');
            $table->index('payment_status', 'orders_payment_status_index');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('is_admin', 'users_is_admin_index');
            $table->index('is_blocked', 'users_is_blocked_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_category_is_active_index');
            $table->dropIndex('products_is_active_index');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_status_index');
            $table->dropIndex('orders_payment_status_index');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_is_admin_index');
            $table->dropIndex('users_is_blocked_index');
        });
    }
};
