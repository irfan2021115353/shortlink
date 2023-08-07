<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Models\ShortLink;
use Illuminate\Support\Facades\Log;
use Str;

class ShortLinkController extends Controller
{
    public function index()
    {
        // Retrieve the latest short links and cache the results
        $shortLinks = Cache::remember('latest_short_links', now()->addMinutes(5), function () {
            return ShortLink::latest()->get();
        });

        return view('shortenLink', compact('shortLinks'));
    }

    public function store(Request $request)
    {
        $request->validate(['link' => 'required|url']);

        $input['link'] = $request->link;
        $input['code'] = Str::random(6);

        ShortLink::create($input);

        return redirect('generate-shorten-link')->withSuccess('Shorten link generated successfully!');
    }

    public function shortenLink($code)
    {
        // Retrieve the link from the cache if available
        $link = Cache::remember('shortened_link_' . $code, now()->addMinutes(5), function () use ($code) {
            $find = ShortLink::where('code', $code)->first();
            return $find ? $find->link : null;
        });
    
        if ($link) {
            return redirect($link);
        } else {
            // Handle the case when the short link is not found
            abort(404, 'The short link was not found.');
        }
    }

    public function getShortenedUrls()
    {
        $cacheKey = 'short_links_data'; // Choose a unique key for the cached data
    
        if (Cache::has($cacheKey)) {
            // Data is available in the cache, retrieve it
            $shortenedUrls = Cache::get($cacheKey);
            Log::debug('Data retrieved from cache: ' . json_encode($shortenedUrls));
        } else {
            // Data not found in cache, fetch it from the database
            $shortenedUrls = ShortenedUrl::all();
            Log::debug('Data fetched from the database: ' . json_encode($shortenedUrls));
    
            // Cache the data for a specific duration (e.g., 5 minutes)
            Cache::put($cacheKey, $shortenedUrls, now()->addMinutes(5));
        }
    
        return response()->json($shortenedUrls);
    }
    
    public function getDataFromDatabase()
    {
        $cacheKey = 'short_link_data'; // Choose a unique key for the cached data
    
        if (Cache::has($cacheKey)) {
            // Data is available in the cache, retrieve it
            $data = Cache::get($cacheKey);
        } else {
            // Data not found in cache, fetch it from the database
            $data = ShortLink::all(); 
    
            // Cache the data for a specific duration (e.g., 5 minutes)
            Cache::put($cacheKey, $data, now()->addMinutes(5));
        }
    
        // Process the retrieved data as needed
    
        return $data;
    }
    
    
    public function getData()
    {
        $cacheKey = 'user_data'; // Choose a unique key for the cached data

        if (Cache::has($cacheKey)) {
            // Data is available in the cache, retrieve it
            $data = Cache::get($cacheKey);
            Log::info('Data retrieved from cache: ' . json_encode($data));
        } else {
            // Data not found in cache, fetch it from the database
            $data = YourModel::all(); // Replace with your actual query or data fetching logic
            Log::info('Data fetched from the database: ' . json_encode($data));

            // Cache the data for a specific duration (e.g., 5 minutes)
            Cache::put($cacheKey, $data, now()->addMinutes(5));
        }

        // Process the retrieved data as needed
        // ...

        return view('shortenLink', compact('data'));
    }

}
