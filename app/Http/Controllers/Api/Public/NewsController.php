<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json(
            Post::with('category', 'author')
                ->where('status', 'published')
                ->when($request->category_id, fn ($q) => $q->where('category_id', $request->category_id))
                ->when($request->search, fn ($q) => $q->where('title', 'like', "%{$request->search}%"))
                ->latest()
                ->paginate(10)
        );
    }

    public function show(Post $post): JsonResponse
    {
        if ($post->status !== 'published') {
            return response()->json(['message' => 'Not found.'], 404);
        }

        return response()->json($post->load('category', 'author'));
    }

    public function events(Request $request): JsonResponse
    {
        return response()->json(
            Event::when($request->upcoming, fn ($q) => $q->where('start_date', '>=', now()))
                ->latest()
                ->paginate(10)
        );
    }
}
