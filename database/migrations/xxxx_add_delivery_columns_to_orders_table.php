<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'delivery_status')) {
                $table->enum('delivery_status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending')->after('status');
            }
            if (!Schema::hasColumn('orders', 'estimated_delivery_date')) {
                $table->date('estimated_delivery_date')->nullable()->after('delivery_status');
            }
            if (!Schema::hasColumn('orders', 'actual_delivery_date')) {
                $table->date('actual_delivery_date')->nullable()->after('estimated_delivery_date');
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_status', 'estimated_delivery_date', 'actual_delivery_date']);
        });
    }
};