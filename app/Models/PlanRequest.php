<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanRequest extends Model
{
    protected $fillable = [
        'user_id','plan_name','plan_level','amount','profit_rate',
        'duration_days','cycle_number','max_cycles',
        'status','notes','admin_note','seen_by_admin',
    ];

    protected $casts = [
        'seen_by_admin' => 'boolean',
        'amount'        => 'decimal:2',
        'profit_rate'   => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dailyProfit(): float
    {
        return round((float)$this->amount * ((float)$this->profit_rate / 100), 2);
    }

    public function totalProfit(): float
    {
        return round($this->dailyProfit() * $this->duration_days, 2);
    }

    // Unseen pending requests count (for badge)
    public static function unseenCount(): int
    {
        return static::where('status', 'pending')->where('seen_by_admin', false)->count();
    }
}
