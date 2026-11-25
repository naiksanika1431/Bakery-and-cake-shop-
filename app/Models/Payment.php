<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'transaction_id',
        'payment_reference',
        'payment_method',
        'payment_type',
        'amount',
        'payment_status',
        'payment_gateway',
        'gateway_transaction_id',
        'gateway_response',
        'refund_amount',
        'refund_reason',
        'refunded_at',
        'notes',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'refund_amount' => 'decimal:2',
            'gateway_response' => 'array',
            'refunded_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    // Parent order
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
