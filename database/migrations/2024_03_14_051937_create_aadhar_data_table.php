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
        Schema::create('aadhar_data', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->unique();
            $table->string('ref_id')->unique();
            $table->string('aadhar_number')->unique();
            $table->string('transaction_id');
            $table->string('timestamp');
            $table->json('core');
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aadhar_data');
    }
};
