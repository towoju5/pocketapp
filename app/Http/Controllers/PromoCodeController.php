<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    public function index()
    {
        try {
            return view('finance.promo-codes');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
