<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deposit_infos', function (Blueprint $table) {
            $table->id();
            // NULL = global (all customers); a user_id = override for that specific customer
            $table->foreignId('user_id')->nullable()->unique()->constrained()->onDelete('cascade');
            $table->string('binance_link')->nullable();
            $table->string('wallet_address')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('swift')->nullable();
            $table->string('reference')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deposit_infos');
    }
};
