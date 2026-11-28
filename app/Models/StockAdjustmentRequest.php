<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAdjustmentRequest extends Model
{
    protected $fillable = [
        'branch_product_id',
        'quantity',
        'reason',
        'requested_by',
        'status',
        'approved_by',
        'reviewed_at',
        'approval_remarks',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:3',
            'reviewed_at' => 'datetime',
        ];
    }

    public function branchProduct(): BelongsTo
    {
        return $this->belongsTo(BranchProduct::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
