<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('withdrawal_infos', function (Blueprint $table) {
            $table->dropColumn([
                'bank_account_name',
                'bank_account_no',
                'bank_name',
                'swift',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('withdrawal_infos', function (Blueprint $table) {
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_no')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('swift')->nullable();
        });
    }
};
