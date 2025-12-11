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
       
   Schema::table('orders', function (Blueprint $table) {
    // Only add new columns that do NOT exist
    if (!Schema::hasColumn('orders', 'delivery_fee')) {
        $table->decimal('delivery_fee', 10, 2)->default(0)->after('total_price');
    }

    if (!Schema::hasColumn('orders', 'coupon_code')) {
        $table->string('coupon_code', 50)->nullable()->after('delivery_fee');
    }

    // Don't add 'address' or 'phone' since they already exist
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['phone', 'delivery_fee', 'coupon_code']);
        });
    }
};
