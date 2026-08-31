<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Event;
use App\Models\Post;
use App\Models\TefaProduct;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    /**
     * GET /api/public/home
     * Returns all data needed for the school's landing page.
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'banners' => Banner::orderBy('sort_order')->get(),
            'news' => Post::with('category')
                ->where('status', 'published')
                ->latest()
                ->take(5)
                ->get(),
            'events' => Event::where('event_date', '>=', now()->toDateString())
                ->orderBy('event_date')
                ->take(5)
                ->get(),
            'products' => TefaProduct::where('stock', '>', 0)->latest()->take(6)->get(),
        ]);
    }
}
