<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\ShortLink;

class CacheController extends Controller
{
    public function cache()
    {
        $seconds = 600;

        $result = Cache::remember('cache-key', $seconds, function () {
            // Expensive database query or data retrieval
            return DB::table('code')->get();
        });

        // Use the cached result
        return view('shortenLink', ['result' => $result]);
    }

    public function clearCache()
    {
        Cache::forget('cache-key');
        // Add more cache keys to forget if needed
        return redirect()->back()->with('success', 'Cache cleared successfully.');
    }

}
