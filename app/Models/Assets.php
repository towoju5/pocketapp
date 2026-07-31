<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assets extends Model
{
    use HasFactory, SoftDeletes;

    // Mass assignable fields
    protected $fillable = [
        'symbol',
        'name',
        'asset_group',
        'exchange_float',
        'asset_profit_margin',
        'extra_data',
        'is_otc',
        'price_source',
        'is_active'
    ];

    // If you have timestamps and soft deletes
    protected $dates = ['deleted_at'];

    protected $casts = [
        'extra_data' => 'array',
        'is_otc' => 'boolean',
        'is_active' => 'boolean'
    ];
}
