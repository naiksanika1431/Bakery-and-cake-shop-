<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomCakeNotificationLog extends Model
{
    protected $fillable = [
        'order_id',
        'channel',
        'provider',
        'recipient',
        'message_body',
        'status',
        'provider_message_id',
        'provider_response',
        'error_message',
        'initiated_by',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'provider_response' => 'array',
            'sent_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function initiatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }
}