<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Supplier extends Model
{
    protected $fillable = [
        'company_id',
        'supplier_code',
        'supplier_name',
        'supplier_type',
        'contact_person',
        'phone',
        'alternate_phone',
        'email',
        'gst_number',
        'pan_number',
        'address',
        'city',
        'state',
        'pincode',
        'payment_terms',
        'credit_limit',
        'notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'credit_limit' => 'decimal:2',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}