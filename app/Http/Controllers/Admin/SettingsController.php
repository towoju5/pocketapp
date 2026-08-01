<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $safeboxEnabled = is_safebox_enabled();
        $defaultChartProvider = get_option('default_chart_provider', 'iqcent');
        $activeChartProvider = get_option('active_chart_provider', 'all');
        $deepseekKeySet = (bool) (get_option('deepseek_api_key') ?: config('services.deepseek.api_key'));

        return view('admin.settings.index', compact(
            'safeboxEnabled',
            'defaultChartProvider',
            'activeChartProvider',
            'deepseekKeySet'
        ));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'default_chart_provider' => 'required|in:iqcent,brokeret',
            'active_chart_provider' => 'required|in:iqcent,brokeret,all',
            'deepseek_api_key' => 'nullable|string|max:255',
        ]);

        set_option('safebox_enabled', $request->has('safebox_enabled') ? '1' : '0');
        set_option('default_chart_provider', $validated['default_chart_provider']);
        set_option('active_chart_provider', $validated['active_chart_provider']);

        if ($request->has('clear_deepseek_api_key')) {
            set_option('deepseek_api_key', '');
        } elseif (!empty($validated['deepseek_api_key'])) {
            set_option('deepseek_api_key', $validated['deepseek_api_key']);
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
