<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCodeRedemption extends Model
{
    protected $fillable = ['user_id', 'promo_code_id', 'amount_credited'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class);
    }
}
