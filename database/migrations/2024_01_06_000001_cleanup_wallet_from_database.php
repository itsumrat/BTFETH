<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Remove wallet_balance column from users
        if (Schema::hasColumn('users', 'wallet_balance')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('wallet_balance');
            });
        }

        // Update transactions type enum - remove wallet_recharge, keep existing data as deposit
        DB::statement("UPDATE transactions SET type='deposit' WHERE type='wallet_recharge'");
        DB::statement("ALTER TABLE transactions MODIFY COLUMN type ENUM('deposit','withdraw') NOT NULL");

        // Clean up [Main Balance] and [Wallet] suffixes from reference column
        DB::statement("UPDATE transactions SET reference = TRIM(TRAILING ' [Main Balance]' FROM reference) WHERE reference LIKE '% [Main Balance]'");
        DB::statement("UPDATE transactions SET reference = TRIM(TRAILING ' [Wallet]' FROM reference) WHERE reference LIKE '% [Wallet]'");
        DB::statement("UPDATE transactions SET reference = TRIM(TRAILING ' [from:Wallet]' FROM reference) WHERE reference LIKE '% [from:Wallet]'");
        DB::statement("UPDATE transactions SET reference = TRIM(TRAILING ' [from:Main Balance]' FROM reference) WHERE reference LIKE '% [from:Main Balance]'");
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('wallet_balance', 15, 2)->default(0)->after('balance');
        });
        DB::statement("ALTER TABLE transactions MODIFY COLUMN type ENUM('deposit','withdraw','wallet_recharge') NOT NULL");
    }
};
