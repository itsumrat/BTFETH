<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deposit_infos', function (Blueprint $table) {
            $table->string('trust_wallet_address')->nullable()->after('wallet_address');
            $table->string('trust_network')->nullable()->after('trust_wallet_address'); // e.g. BEP-20, ERC-20, TRC-20
        });

        Schema::table('withdrawal_infos', function (Blueprint $table) {
            $table->string('trust_withdraw_address')->nullable()->after('withdraw_id');
            $table->string('trust_network')->nullable()->after('trust_withdraw_address');
        });
    }

    public function down(): void
    {
        Schema::table('deposit_infos', function (Blueprint $table) {
            $table->dropColumn(['trust_wallet_address', 'trust_network']);
        });
        Schema::table('withdrawal_infos', function (Blueprint $table) {
            $table->dropColumn(['trust_withdraw_address', 'trust_network']);
        });
    }
};
