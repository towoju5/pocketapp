<?php

use App\Models\Assets;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

if (!function_exists('get_assets')) {
    function get_assets()
    {
        $assets = Assets::all();
        return $assets;
    }
}

if (!function_exists('tableExists')) {
    function tableExists($tableName)
    {
        // Method 1: Using Schema facade
        return Schema::hasTable($tableName);
    }
}

if (!function_exists('checkMultipleTables')) {

    function checkMultipleTables(array $tableNames)
    {
        // Method 2: Using DB facade to check multiple tables
        $existingTables = DB::select("
            SELECT table_name 
            FROM information_schema.tables 
            WHERE table_schema = ?
            AND table_name IN ('" . implode("','", $tableNames) . "')",
            [env('DB_DATABASE')]
        );

        return collect($existingTables)->pluck('table_name')->toArray();
    }
}