<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('payment_status', ['pending', 'awaiting_payment', 'paid', 'failed'])->default('pending')->after('payment_method');
            $table->string('bank_receipt')->nullable()->after('payment_status');
            $table->text('notes')->nullable()->after('bank_receipt');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'bank_receipt', 'notes']);
        });
    }
};