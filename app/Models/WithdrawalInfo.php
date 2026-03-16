<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawalInfo extends Model
{
    protected $fillable = [
        'user_id', 'withdraw_link', 'withdraw_id',
        'trust_withdraw_address', 'trust_network',
        'min_withdrawal', 'processing_time', 'fee', 'note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isOverride(): bool
    {
        return $this->user_id !== null;
    }
}
