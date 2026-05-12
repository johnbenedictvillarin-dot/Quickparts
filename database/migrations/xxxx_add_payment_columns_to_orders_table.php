<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add payment_status column
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->enum('payment_status', ['pending', 'awaiting_payment', 'paid', 'failed'])->default('pending')->after('payment_method');
            }
            
            // Add bank_receipt column
            if (!Schema::hasColumn('orders', 'bank_receipt')) {
                $table->string('bank_receipt')->nullable()->after('payment_status');
            }
            
            // Add notes column
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