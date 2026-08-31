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
            'banners'   => Banner::where('is_active', true)->orderBy('order')->get(),
            'news'      => Post::with('category')
                ->where('status', 'published')
                ->latest('published_at')
                ->take(5)
                ->get(),
            'events'    => Event::where('status', 'published')
                ->where('start_date', '>=', now())
                ->orderBy('start_date')
                ->take(5)
                ->get(),
            'products'  => TefaProduct::where('is_featured', true)->take(6)->get(),
        ]);
    }
}
