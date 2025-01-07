<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OptionController extends Controller
{
    protected $guarded = [];

    protected $fillable = [
        "option_name",
        "option_value",
        "autoload"
    ];
}
