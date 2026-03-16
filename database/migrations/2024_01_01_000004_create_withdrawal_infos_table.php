<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('withdrawal_infos', function (Blueprint $table) {
            $table->id();
            // NULL = global; user_id = customer-specific override
            $table->foreignId('user_id')->nullable()->unique()->constrained()->onDelete('cascade');
            $table->string('withdraw_link')->nullable();
            $table->string('withdraw_id')->nullable();
            $table->string('min_withdrawal')->nullable();
            $table->string('processing_time')->nullable();
            $table->string('fee')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_no')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('swift')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawal_infos');
    }
};
