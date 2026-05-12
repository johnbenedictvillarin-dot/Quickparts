<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Check if column exists before adding
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('orders', 'bank_receipt')) {
                $table->string('bank_receipt')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('orders', 'notes')) {
                $table->text('notes')->nullable()->after('bank_receipt');
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'bank_receipt', 'notes']);
        });
    }
};