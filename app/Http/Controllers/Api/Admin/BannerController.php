<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function __construct(private ActivityLogService $logger) {}

    public function index(): JsonResponse
    {
        return response()->json(Banner::orderBy('sort_order')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'image_url'  => ['required', 'string'],
            'link_url'   => ['nullable', 'url'],
            'sort_order' => ['integer'],
        ]);

        $banner = Banner::create($data);
        $this->logger->log($request->user()->id, 'created', 'banners');

        return response()->json(['message' => 'Banner created.', 'banner' => $banner], 201);
    }

    public function show(Banner $banner): JsonResponse
    {
        return response()->json($banner);
    }

    public function update(Request $request, Banner $banner): JsonResponse
    {
        $data = $request->validate([
            'image_url'  => ['sometimes', 'string'],
            'link_url'   => ['nullable', 'url'],
            'sort_order' => ['integer'],
        ]);

        $banner->update($data);
        $this->logger->log($request->user()->id, 'updated', 'banners');

        return response()->json(['message' => 'Banner updated.', 'banner' => $banner]);
    }

    public function destroy(Request $request, Banner $banner): JsonResponse
    {
        $this->logger->log($request->user()->id, 'deleted', 'banners');
        $banner->delete();

        return response()->json(['message' => 'Banner deleted.']);
    }
}
