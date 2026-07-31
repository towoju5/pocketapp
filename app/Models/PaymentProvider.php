<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentProvider extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'bool',
        'can_deposit' => 'bool',
        'can_payout' => 'bool',
        'credentials' => 'encrypted:array',
        'config' => 'array',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Read a single credential field, e.g. credential('secret_key').
     */
    public function credential(string $key, mixed $default = null): mixed
    {
        return ($this->credentials ?? [])[$key] ?? $default;
    }
}
