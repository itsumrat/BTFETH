<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Investment extends Model
{
    protected $fillable = [
        'user_id', 'plan_name', 'amount', 'profit_percent',
        'duration_days', 'total_cycles', 'completed_cycles',
        'start_date', 'end_date', 'status', 'notes',
    ];

    protected $casts = [
        'amount'            => 'decimal:2',
        'profit_percent'    => 'decimal:2',
        'start_date'        => 'date',
        'end_date'          => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function daysRemaining(): int
    {
        if ($this->status !== 'active') return 0;
        $diff = now()->diffInDays($this->end_date, false);
        return max(0, (int) $diff);
    }

    public function daysElapsed(): int
    {
        return (int) $this->start_date->diffInDays(now());
    }

    public function progressPercent(): int
    {
        $total = $this->duration_days;
        if ($total <= 0) return 100;
        $elapsed = min($this->daysElapsed(), $total);
        return (int) round(($elapsed / $total) * 100);
    }

    public function estimatedProfit(): float
    {
        return round($this->amount * ($this->profit_percent / 100), 2);
    }

    public function planBadgeColor(): string
    {
        return match(true) {
            str_contains($this->plan_name, 'VIP 2') => '#f59e0b',
            str_contains($this->plan_name, 'VIP 1') => '#a78bfa',
            str_contains($this->plan_name, 'Plan 2') => '#3b82f6',
            default => '#22c55e',
        };
    }
}
