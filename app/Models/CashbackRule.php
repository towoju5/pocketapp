<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashbackRule extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['type', 'percentage', 'volume_threshold', 'promo_code', 'active'];
}
