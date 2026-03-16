<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepositInfo extends Model
{
    protected $fillable = [
        'user_id', 'binance_link', 'wallet_address',
        'trust_wallet_address', 'trust_network',
        'account_name', 'account_number', 'bank_name', 'swift', 'reference',
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
