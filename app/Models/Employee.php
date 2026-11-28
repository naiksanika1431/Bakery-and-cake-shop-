<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    protected $fillable = [
        'company_id',
        'branch_id',
        'employee_code',
        'name',
        'photo',
        'date_of_birth',
        'gender',
        'mobile',
        'email',
        'address',
        'department',
        'designation',
        'joining_date',
        'employment_type',
        'shift',
        'salary',
        'payment_type',
        'emergency_contact_name',
        'emergency_contact_mobile',
        'emergency_contact_relation',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'joining_date' => 'date',
            'salary' => 'decimal:2',
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
}
