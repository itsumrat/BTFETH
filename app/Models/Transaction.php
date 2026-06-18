<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 'type', 'amount', 'reference', 'status', 'transaction_date',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'amount'           => 'decimal:2',
    ];

    // Include soft-deleted users so transactions still show their name
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function isDeposit(): bool
    {
        return $this->type === 'deposit';
    }

    public function typeLabel(): string
    {
        return match($this->type) {
            'deposit'  => 'Deposit',
            'withdraw' => 'Withdraw',
            default    => ucfirst($this->type),
        };
    }

    public function signedAmount(): string
    {
        return ($this->isDeposit() ? '+' : '-') . '$' . number_format($this->amount, 2);
    }

    public function statusBadgeClass(): string
    {
        return match($this->status) {
            'completed' => 'badge-green',
            'pending'   => 'badge-gold',
            'failed'    => 'badge-red',
            default     => 'badge-blue',
        };
    }
}
