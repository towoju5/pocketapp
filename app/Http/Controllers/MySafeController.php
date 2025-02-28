<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MySafeController extends Controller
{
    public function index()
    {
        try {
            return view('finance.my-safe');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
