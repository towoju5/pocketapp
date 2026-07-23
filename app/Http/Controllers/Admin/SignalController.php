<?php

namespace App\Http\Controllers\Admin;

use App\Events\SignalCreated;
use App\Http\Controllers\Controller;
use App\Models\Assets;
use App\Services\AiSignalService;
use Illuminate\Http\Request;
use App\Models\Signal;

class SignalController extends Controller
{
    public function index()
    {
        $signals = Signal::latest()->paginate(10);
        return view('admin.signals.index', compact('signals'));
    }

    /** Picks a currently-online asset by recent trend (AI-assisted when DEEPSEEK_API_KEY is set) and publishes it as a signal. */
    public function generateAi(AiSignalService $aiSignal)
    {
        try {
            $aiSignal->generate(auth()->id());
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.signals.index')->with('success', 'AI-generated signal published.');
    }

    public function destroy(Signal $signal)
    {
        $signal->delete();

        return redirect()->route('admin.signals.index')->with('success', 'Signal removed.');
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