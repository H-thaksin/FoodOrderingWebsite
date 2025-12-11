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
        Schema::table('order_items', function (Blueprint $table) {
        if (!Schema::hasColumn('order_items', 'food_name')) {
            $table->string('food_name')->nullable()->after('food_id');
        }
        if (!Schema::hasColumn('order_items', 'food_image')) {
            $table->string('food_image')->nullable()->after('food_name');
        }
        if (!Schema::hasColumn('order_items', 'unit_price')) {
            $table->decimal('unit_price', 10, 2)->nullable()->after('food_image');
        }
        if (!Schema::hasColumn('order_items', 'subtotal')) {
            $table->decimal('subtotal', 10, 2)->nullable()->after('unit_price');
        }
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            //
        });
    }
};
