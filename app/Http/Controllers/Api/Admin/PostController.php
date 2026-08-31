<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function __construct(private ActivityLogService $logger) {}

    public function index(Request $request): JsonResponse
    {
        $posts = Post::with(['author', 'category'])
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->search, fn ($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(15);

        return response()->json($posts);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'content'     => ['required', 'string'],
            'image_url'   => ['nullable', 'string'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'status'      => ['in:draft,published'],
        ]);

        $data['author_id'] = $request->user()->id;
        $data['slug']      = Str::slug($data['title']) . '-' . uniqid();
        $data['status']    = $data['status'] ?? 'draft';

        $post = Post::create($data);
        $this->logger->log($request->user()->id, 'created', 'posts');

        return response()->json(['message' => 'Post created.', 'post' => $post->load('author', 'category')], 201);
    }

    public function show(Post $post): JsonResponse
    {
        return response()->json($post->load('author', 'category'));
    }

    public function update(Request $request, Post $post): JsonResponse
    {
        $data = $request->validate([
            'title'       => ['sometimes', 'string', 'max:255'],
            'content'     => ['sometimes', 'string'],
            'image_url'   => ['nullable', 'string'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'status'      => ['in:draft,published'],
        ]);

        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']) . '-' . $post->id;
        }

        $post->update($data);
        $this->logger->log($request->user()->id, 'updated', 'posts');

        return response()->json(['message' => 'Post updated.', 'post' => $post->fresh('author', 'category')]);
    }

    public function destroy(Request $request, Post $post): JsonResponse
    {
        $this->logger->log($request->user()->id, 'deleted', 'posts');
        $post->delete();

        return response()->json(['message' => 'Post deleted.']);
    }
}
