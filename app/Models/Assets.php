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
        'exchange_float_type',
        'exchange_float',
    ];

    // If you have timestamps and soft deletes
    protected $dates = ['deleted_at'];
}
