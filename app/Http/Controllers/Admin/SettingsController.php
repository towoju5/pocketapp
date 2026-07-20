<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $safeboxEnabled = is_safebox_enabled();

        return view('admin.settings.index', compact('safeboxEnabled'));
    }

    public function update(Request $request)
    {
        set_option('safebox_enabled', $request->has('safebox_enabled') ? '1' : '0');

        return back()->with('success', 'Settings updated successfully.');
    }
}
