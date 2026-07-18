<?php

namespace App\Http\Controllers;

use App\Models\Signal;

class SignalController extends Controller
{
    public function index()
    {
        $signals = Signal::latest()->where('is_active', true)->get();

        return view('signals.index', compact('signals'));
    }
}
