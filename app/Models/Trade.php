<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    protected $guarded = [];

    protected $casts = [
        "trade_extra_info" => "array",
    ];
}
