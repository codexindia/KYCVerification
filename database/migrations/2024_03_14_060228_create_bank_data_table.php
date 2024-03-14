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
        Schema::create('bank_data', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('ifsc_code');
            $table->string('account_number')->unique();
            $table->string('status')->default('verified');
            $table->string('api_transaction_id');
            $table->string('timestamp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_data');
    }
};
