<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentPlan extends Model
{
    protected $fillable = [
        'user_id','plan_name','plan_level','max_cycles','cycle_number',
        'amount','profit_rate','duration_days','start_date','end_date',
        'status','notes',
    ];

    protected $casts = [
        'start_date'  => 'datetime',
        'end_date'    => 'datetime',
        'amount'      => 'decimal:2',
        'profit_rate' => 'decimal:2',
    ];

    public static array $planConfig = [
        'Plan 1' => ['level'=>1,'rate_min'=>1.5,'rate_max'=>1.5,'min'=>500,  'max'=>1000, 'days'=>7,  'max_cycles'=>2,'color'=>'#f59e0b'],
        'Plan 2' => ['level'=>2,'rate_min'=>3.0,'rate_max'=>3.5,'min'=>10000,'max'=>15000,'days'=>15, 'max_cycles'=>3,'color'=>'#3b82f6'],
        'VIP 1'  => ['level'=>3,'rate_min'=>5.0,'rate_max'=>5.0,'min'=>25000,'max'=>50000,'days'=>28, 'max_cycles'=>6,'color'=>'#22c55e'],
        'VIP 2'  => ['level'=>4,'rate_min'=>7.0,'rate_max'=>7.0,'min'=>50000,'max'=>999999,'days'=>28,'max_cycles'=>5,'color'=>'#a855f7'],
    ];

    // Include soft-deleted users
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function dailyProfit(): float
    {
        return round((float)$this->amount * ((float)$this->profit_rate / 100), 2);
    }

    public function totalExpectedProfit(): float
    {
        return round($this->dailyProfit() * $this->duration_days, 2);
    }

    public function progressPercent(): int
    {
        $total   = max(1, $this->start_date->diffInDays($this->end_date));
        $elapsed = min($total, $this->start_date->diffInDays(now()));
        return (int) round(($elapsed / $total) * 100);
    }

    public function daysRemaining(): int
    {
        return max(0, (int) now()->diffInDays($this->end_date, false));
    }

    public static function cyclesUsed(int $userId, string $planName): int
    {
        return static::where('user_id', $userId)
            ->where('plan_name', $planName)
            ->whereIn('status', ['active', 'completed'])
            ->count();
    }

    public static function canStartCycle(int $userId, string $planName, int $maxCycles): array
    {
        $used      = static::cyclesUsed($userId, $planName);
        $hasActive = static::where('user_id', $userId)
            ->where('plan_name', $planName)
            ->where('status', 'active')
            ->exists();

        if ($hasActive) return ['allowed' => false, 'reason' => 'Active cycle already running'];
        if ($used >= $maxCycles) return ['allowed' => false, 'reason' => "Max {$maxCycles} cycles reached"];
        return ['allowed' => true, 'cycle' => $used + 1];
    }
}
