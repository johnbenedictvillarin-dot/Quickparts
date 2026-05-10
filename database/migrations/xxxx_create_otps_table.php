<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            $table->string('email');  // This column exists
            $table->string('otp');
            $table->boolean('is_verified')->default(false);
            $table->timestamp('expires_at');
            $table->timestamps();
            
            // Add index for faster queries
            $table->index('email');
            $table->index('otp');
        });
    }

    public function down()
    {
        Schema::dropIfExists('otps');
    }
};