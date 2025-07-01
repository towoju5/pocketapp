<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payout extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = ["payout_extra_info" => "array"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payout_method()
    {
        return $this->belongsTo(Bitgo::class);
    }
}
