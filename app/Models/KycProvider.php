<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KycProvider extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'bool',
        'credentials' => 'encrypted:array',
        'config' => 'array',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Read a single credential field, e.g. credential('api_key').
     */
    public function credential(string $key, mixed $default = null): mixed
    {
        return ($this->credentials ?? [])[$key] ?? $default;
    }
}
