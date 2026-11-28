<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BranchProduct extends Model
{
    protected $fillable = [
        'branch_id',
        'product_variant_id',
        'selling_price',
        'min_stock',
        'stock_quantity',
        'is_available',
        'is_enabled',
        'online_available',
    ];

    protected function casts(): array
    {
        return [
            'selling_price' => 'decimal:2',
            'min_stock' => 'decimal:3',
            'stock_quantity' => 'decimal:3',
            'is_available' => 'boolean',
            'is_enabled' => 'boolean',
            'online_available' => 'boolean',
        ];
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function adjustmentRequests(): HasMany
    {
        return $this->hasMany(StockAdjustmentRequest::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
