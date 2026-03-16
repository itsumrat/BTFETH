<?php

namespace Database\Seeders;

use App\Models\{User, Transaction, DepositInfo, WithdrawalInfo};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin user ──────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'admin@onchain.finance'],
            [
                'name'      => 'Admin',
                'password'  => Hash::make('admin123'),
                'is_admin'  => true,
                'is_active' => true,
            ]
        );

        // ── Demo customers ──────────────────────────────────
        $customers = [
            ['name' => 'John Doe',   'email' => 'john@email.com',  'balance' => 12450.00, 'total_deposited' => 10000, 'total_withdrawn' => 800,  'daily_profit' => 124.50],
            ['name' => 'Sarah Ali',  'email' => 'sarah@email.com', 'balance' => 5800.00,  'total_deposited' => 5500,  'total_withdrawn' => 1200, 'daily_profit' => 58.00],
            ['name' => 'Mike Khan',  'email' => 'mike@email.com',  'balance' => 25000.00, 'total_deposited' => 25000, 'total_withdrawn' => 0,    'daily_profit' => 1250.00],
            ['name' => 'Rita Lee',   'email' => 'rita@email.com',  'balance' => 3000.00,  'total_deposited' => 3000,  'total_withdrawn' => 500,  'daily_profit' => 45.00],
            ['name' => 'Tom Nguyen', 'email' => 'tom@email.com',   'balance' => 7200.00,  'total_deposited' => 7200,  'total_withdrawn' => 1000, 'daily_profit' => 108.00],
            ['name' => 'Lena Bauer', 'email' => 'lena@email.com',  'balance' => 50000.00, 'total_deposited' => 50000, 'total_withdrawn' => 5000, 'daily_profit' => 3500.00],
        ];

        foreach ($customers as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                array_merge($data, [
                    'password'  => Hash::make('password123'),
                    'is_admin'  => false,
                    'is_active' => true,
                ])
            );

            Transaction::create([
                'user_id'          => $user->id,
                'type'             => 'deposit',
                'amount'           => $data['total_deposited'],
                'reference'        => 'Binance Pay',
                'status'           => 'completed',
                'transaction_date' => now()->subDays(rand(5, 30)),
            ]);

            if ($data['total_withdrawn'] > 0) {
                Transaction::create([
                    'user_id'          => $user->id,
                    'type'             => 'withdraw',
                    'amount'           => $data['total_withdrawn'],
                    'reference'        => 'Bank Transfer',
                    'status'           => 'completed',
                    'transaction_date' => now()->subDays(rand(1, 4)),
                ]);
            }
        }

        // ── Global Deposit Info ─────────────────────────────
        DepositInfo::updateOrCreate(
            ['user_id' => null],
            [
                'binance_link'   => 'https://pay.binance.com/en/checkout/oc_send?payeeId=ONChain_19283746',
                'wallet_address' => '0x4a7c2F3bD19E83746A0cF5e8472Da3B1290c8E4F',
                'account_name'   => 'ONCHAIN INVESTMENTS LTD',
                'account_number' => '1029 3847 5610 2938',
                'bank_name'      => 'Digital Reserve Bank',
                'swift'          => 'DRBUS33 / 021000021',
                'reference'      => 'OC-DEP-7829',
            ]
        );

        // ── Global Withdrawal Info ──────────────────────────
        WithdrawalInfo::updateOrCreate(
            ['user_id' => null],
            [
                'withdraw_link'   => 'https://pay.binance.com/en/withdrawal/request?ref=ONChain_WD_7829A',
                'withdraw_id'     => 'WD-ONX-2026-7829-ALPHA',
                'min_withdrawal'  => '$50 USDT',
                'processing_time' => '12-24 Hours',
                'fee'             => '1.5% of amount',
                'note'            => 'Contact live chat to submit your withdrawal request with your wallet address.',
            ]
        );

        $this->command->info('Database seeded!');
        $this->command->info('Admin:    admin@onchain.finance / admin123');
        $this->command->info('Customer: john@email.com / password123');
    }
}
