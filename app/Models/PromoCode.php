<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class PromoCode extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'promo_title',
        'promo_code',
        'promo_discount',
        'promo_created_by',
        'promo_discount_type',
        'promo_start_date_time',
        'promo_ends_date_time',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'promo_created_by');
    }

    public function redemptions()
    {
        return $this->hasMany(PromoCodeRedemption::class);
    }

    public function scopeCurrentlyValid($query)
    {
        $now = now()->toDateTimeString();

        return $query->where('promo_start_date_time', '<=', $now)
            ->where('promo_ends_date_time', '>=', $now);
    }

    public function isValidNow(): bool
    {
        $now = Carbon::now();

        return Carbon::parse($this->promo_start_date_time)->lte($now)
            && Carbon::parse($this->promo_ends_date_time)->gte($now);
    }
}
