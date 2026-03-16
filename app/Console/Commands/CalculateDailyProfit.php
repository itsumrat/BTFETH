<?php

namespace App\Console\Commands;

use App\Models\{User, Transaction};
use Illuminate\Console\Command;

class CalculateDailyProfit extends Command
{
    protected $signature   = 'profit:calculate';
    protected $description = 'Calculate and credit daily profit for all active customers';

    /**
     * Profit rates per plan (based on total_deposited balance).
     * In production you can replace this with a proper plans table.
     */
    private array $rates = [
        ['min' => 500,    'max' => 1000,   'rate' => 0.015], // Plan 1: 1.5%
        ['min' => 10000,  'max' => 15000,  'rate' => 0.032], // Plan 2: ~3.2%
        ['min' => 25000,  'max' => 50000,  'rate' => 0.050], // VIP 1: 5%
        ['min' => 50000,  'max' => PHP_INT_MAX, 'rate' => 0.070], // VIP 2: 7%
    ];

    public function handle(): void
    {
        $users = User::where('is_admin', false)
                     ->where('is_active', true)
                     ->where('balance', '>', 0)
                     ->get();

        $count = 0;

        foreach ($users as $user) {
            $rate = $this->getRate($user->balance);
            if ($rate === null) continue;

            $profit = round($user->balance * $rate, 2);

            // Credit to balance
            $user->increment('balance', $profit);
            $user->update(['daily_profit' => $profit]);

            // Record as a transaction
            Transaction::create([
                'user_id'          => $user->id,
                'type'             => 'deposit',
                'amount'           => $profit,
                'reference'        => 'Daily Profit (' . ($rate * 100) . '%)',
                'status'           => 'completed',
                'transaction_date' => now(),
            ]);

            $count++;
        }

        $this->info("✓ Daily profit calculated and credited for {$count} customer(s).");
    }

    private function getRate(float $balance): ?float
    {
        foreach ($this->rates as $tier) {
            if ($balance >= $tier['min'] && $balance <= $tier['max']) {
                return $tier['rate'];
            }
        }
        return null;
    }
}
