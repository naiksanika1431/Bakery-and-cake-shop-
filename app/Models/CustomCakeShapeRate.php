<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomCakeShapeRate extends Model
{
    protected $fillable = [
        'company_id',
        'shape_name',
        'min_weight_kg',
        'max_weight_kg',
        'min_dimension_cm',
        'max_dimension_cm',
        'complexity_level',
        'shape_charge',
        'is_active',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'min_weight_kg' => 'decimal:2',
            'max_weight_kg' => 'decimal:2',
            'min_dimension_cm' => 'decimal:2',
            'max_dimension_cm' => 'decimal:2',
            'shape_charge' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}