<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'type', 'amount', 'reference', 'status', 'transaction_date',
    ];

    protected $casts = [
        'amount'           => 'decimal:2',
        'transaction_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isDeposit(): bool
    {
        return $this->type === 'deposit';
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
            default     => 'badge-gray',
        };
    }
}
