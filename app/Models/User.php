<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'avatar', 'email', 'password',
        'balance',
        'wallet_balance', 'daily_profit', 'total_deposited', 'total_withdrawn',
        'is_admin', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    // Laravel 10 uses $casts property (not a method)
    protected $casts = [
        'email_verified_at' => 'datetime',
        'balance'           => 'decimal:2',
        'daily_profit'      => 'decimal:2',
        'total_deposited'   => 'decimal:2',
        'total_withdrawn'   => 'decimal:2',
        'is_admin'          => 'boolean',
        'is_active'         => 'boolean',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function depositInfo()
    {
        return $this->hasOne(DepositInfo::class);
    }

    public function withdrawalInfo()
    {
        return $this->hasOne(WithdrawalInfo::class);
    }

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    public function initials(): string
    {
        $parts = explode(' ', $this->name);
        return strtoupper(
            (isset($parts[0]) ? $parts[0][0] : '') .
            (isset($parts[1]) ? $parts[1][0] : '')
        );
    }
}
