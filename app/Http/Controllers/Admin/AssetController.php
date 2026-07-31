<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assets;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        // Get the asset group / price source filters from the request
        $assetGroup = $request->input('asset_group');
        $priceSource = $request->input('price_source');

        // Query the assets, applying the filters if present
        $query = Assets::query();

        if ($assetGroup) {
            $query->where('asset_group', $assetGroup);
        }
        if ($priceSource) {
            $query->where('price_source', $priceSource);
        }

        // Paginate the results
        $assets = $query->paginate(10)->withQueryString();

        // Pass the list of asset groups to the view for filtering
        $assetGroups = Assets::select('asset_group')->distinct()->pluck('asset_group');

        return view('admin.assets.index', compact('assets', 'assetGroups', 'assetGroup', 'priceSource'))->with('success', 'Page successfully loaded.');
    }


    public function create()
    {
        return view('admin.assets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Uniqueness is now enforced at the DB level too (see the
            // add_unique_index_to_assets_symbol migration) — validating it
            // here as well turns what would otherwise be a raw 500 on a
            // duplicate symbol into a normal form error.
            'symbol' => 'required|max:255|unique:assets,symbol',
            'name' => 'required|max:255',
            'asset_group' => 'required|max:255',
            'exchange_float' => 'required|max:255',
            'asset_profit_margin' => 'nullable|numeric',
            'extra_data' => 'nullable|array',
            'is_otc' => 'nullable|boolean',
            'price_source' => 'required|in:iqcent,brokeret',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle extra_data as JSON if provided
        if (isset($validated['extra_data']) && is_array($validated['extra_data'])) {
            $validated['extra_data'] = $validated['extra_data'];
        }

        Assets::create($validated);

        return redirect()->route('admin.assets.index')->with('success', 'Asset created successfully.');
    }

    public function show(Assets $asset)
    {
        return view('admin.assets.show', compact('asset'));
    }

    public function edit(Assets $asset)
    {
        return view('admin.assets.edit', compact('asset'));
    }

    public function update(Request $request, Assets $asset)
    {
        $validated = $request->validate([
            'symbol' => ['required', 'max:255', Rule::unique('assets', 'symbol')->ignore($asset->id)],
            'name' => 'required|max:255',
            'asset_group' => 'required|max:255',
            'exchange_float' => 'required|max:255',
            'asset_profit_margin' => 'nullable|numeric',
            'extra_data' => 'nullable|array',
            'is_otc' => 'nullable|boolean',
            'price_source' => 'required|in:iqcent,brokeret',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle extra_data as JSON if provided
        if (isset($validated['extra_data']) && is_array($validated['extra_data'])) {
            $validated['extra_data'] = $validated['extra_data'];
        }

        $asset->update($validated);

        return redirect()->route('admin.assets.index')->with('success', 'Asset updated successfully.');
    }

    public function destroy(Assets $asset)
    {
        $asset->delete();

        return redirect()->route('admin.assets.index')->with('success', 'Asset deleted successfully.');
    }
}
