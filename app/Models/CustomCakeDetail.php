<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomCakeDetail extends Model
{
    protected $fillable = [
        'order_id',
        'occasion',
        'required_date',
        'required_time',
        'fulfilment_type',
        'servings',
        'flavour',
        'weight_kg',
        'cake_size',
        'shape',
        'dimensions',
        'dimension_cm',
        'shape_complexity',
        'theme',
        'colour',
        'cream_type',
        'decorations',
        'cake_message',
        'reference_image_path',
        'special_instructions',
        'flavour_charge',
        'shape_charge',
        'theme_decoration_charge',
        'cream_charge',
        'delivery_charge',
        'calculated_total',
        'pricing_snapshot',
    ];

    protected function casts(): array
    {
        return [
            'required_date' => 'date',
            'required_time' => 'datetime:H:i',
            'weight_kg' => 'decimal:2',
            'dimension_cm' => 'decimal:2',
            'flavour_charge' => 'decimal:2',
            'shape_charge' => 'decimal:2',
            'theme_decoration_charge' => 'decimal:2',
            'cream_charge' => 'decimal:2',
            'delivery_charge' => 'decimal:2',
            'calculated_total' => 'decimal:2',
            'pricing_snapshot' => 'array',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
