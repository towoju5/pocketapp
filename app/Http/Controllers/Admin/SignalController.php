<?php

namespace App\Http\Controllers\Admin;

use App\Events\SignalCreated;
use App\Http\Controllers\Controller;
use App\Models\Assets;
use Illuminate\Http\Request;
use App\Models\Signal;

class SignalController extends Controller
{
    public function index()
    {
        $signals = Signal::latest()->paginate(10);
        return view('admin.signals.index', compact('signals'));
    }

    public function create()
    {
        $assets = Assets::all();
        return view('admin.signals.create', compact('assets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'direction' => 'required|in:up,down',
            'duration' => 'required|integer|min:1',
            'expected_profit' => 'nullable|numeric',
            'start_price' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        $signal = Signal::create($validated);
        event(new SignalCreated($signal));
        return redirect()->route('admin.signals.index')->with('success', 'Signal created successfully.');
    }
}