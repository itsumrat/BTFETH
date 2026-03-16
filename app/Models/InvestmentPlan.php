<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentPlan extends Model
{
    protected $fillable = [
        'user_id', 'plan_name', 'plan_level', 'max_cycles',
        'cycle_number', 'amount', 'profit_rate', 'duration_days',
        'start_date', 'end_date', 'status', 'notes',
    ];

    protected $casts = [
        'amount'      => 'decimal:2',
        'profit_rate' => 'decimal:2',
        'start_date'  => 'date',
        'end_date'    => 'date',
    ];

    // Plan definitions
    public static array $planConfig = [
        'Plan 1' => ['level' => 1, 'max_cycles' => 2, 'default_rate' => 1.5, 'max_days' => 7,  'min_amount' => 500,    'max_amount' => 1000],
        'Plan 2' => ['level' => 2, 'max_cycles' => 3, 'default_rate' => 3.2, 'max_days' => 15, 'min_amount' => 10000,  'max_amount' => 15000],
        'VIP 1'  => ['level' => 3, 'max_cycles' => 6, 'default_rate' => 5.0, 'max_days' => 28, 'min_amount' => 25000,  'max_amount' => 50000],
        'VIP 2'  => ['level' => 4, 'max_cycles' => 5, 'default_rate' => 7.0, 'max_days' => 28, 'min_amount' => 50000,  'max_amount' => 999999],
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function daysRemaining(): int
    {
        if ($this->status !== 'active') return 0;
        return max(0, (int) now()->diffInDays($this->end_date, false));
    }

    public function daysElapsed(): int
    {
        return max(0, (int) $this->start_date->diffInDays(now()));
    }

    public function progressPercent(): int
    {
        if ($this->duration_days <= 0) return 0;
        return min(100, (int) round(($this->daysElapsed() / $this->duration_days) * 100));
    }

    public function dailyProfit(): float
    {
        return round((float)$this->amount * ($this->profit_rate / 100), 2);
    }

    public function totalExpectedProfit(): float
    {
        return round($this->dailyProfit() * $this->duration_days, 2);
    }

    // How many cycles used by a user for a given plan
    public static function cyclesUsed(int $userId, string $planName): int
    {
        return self::where('user_id', $userId)
            ->where('plan_name', $planName)
            ->whereIn('status', ['active', 'completed'])
            ->count();
    }

    // Can user start a new cycle in this plan?
    public static function canStartCycle(int $userId, string $planName): array
    {
        $config = self::$planConfig[$planName] ?? null;
        if (!$config) return ['allowed' => false, 'reason' => 'Unknown plan.'];

        $used = self::cyclesUsed($userId, $planName);
        $max  = $config['max_cycles'];

        if ($used >= $max) {
            return [
                'allowed' => false,
                'reason'  => "Maximum cycles reached for {$planName}. This customer has used {$used}/{$max} cycles and must upgrade to a higher plan.",
                'used'    => $used,
                'max'     => $max,
            ];
        }

        // Check no active cycle already running in same plan
        $hasActive = self::where('user_id', $userId)
            ->where('plan_name', $planName)
            ->where('status', 'active')
            ->exists();

        if ($hasActive) {
            return [
                'allowed' => false,
                'reason'  => "This customer already has an active {$planName} cycle running. Complete or cancel it before starting a new one.",
                'used'    => $used,
                'max'     => $max,
            ];
        }

        return ['allowed' => true, 'used' => $used, 'max' => $max];
    }
}
