<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'email',
    'password',
    'company_id',
    'branch_id',
    'role_id',
])]

#[Hidden(['password', 'remember_token'])]

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Company relationship
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    // Branch relationship
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    // Role relationship
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    // Check user permission
    public function hasPermission(string $permissionCode): bool
    {
        return $this->role()
            ->whereHas('permissions', function ($query) use ($permissionCode) {
                $query->where('permission_code', $permissionCode);
            })
            ->exists();
    }
}
