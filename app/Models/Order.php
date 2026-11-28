<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'company_id',
        'branch_id',
        'customer_id',
        'customer_account_id',
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'order_type',
        'invoice_type',
        'delivery_address',
        'delivery_city',
        'delivery_state',
        'delivery_pincode',
        'order_status',
        'payment_status',
        'payment_method',
        'subtotal',
        'discount',
        'tax_amount',
        'delivery_charge',
        'grand_total',
        'advance_requested',
        'expected_completion_at',
        'admin_notes',
        'quotation_status',
        'customer_responded_at',
        'customer_rejection_reason',
        'notes',
        'scheduled_at',
        'placed_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'delivery_charge' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'advance_requested' => 'decimal:2',
            'expected_completion_at' => 'datetime',
            'customer_responded_at' => 'datetime',
            'scheduled_at' => 'datetime',
            'placed_at' => 'datetime',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function customerAccount(): BelongsTo
    {
        return $this->belongsTo(CustomerAccount::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function customCakeDetail(): HasOne
    {
        return $this->hasOne(CustomCakeDetail::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(CustomCakeNotificationLog::class);
    }
}