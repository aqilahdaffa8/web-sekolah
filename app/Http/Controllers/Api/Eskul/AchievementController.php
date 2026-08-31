<?php

namespace App\Http\Controllers\Api\Eskul;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    public function __construct(private ActivityLogService $logger) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json(
            Achievement::with('extracurricular')
                ->when($request->extracurricular_id, fn ($q) => $q->where('extracurricular_id', $request->extracurricular_id))
                ->latest()
                ->paginate(15)
        );
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title'              => ['required', 'string', 'max:255'],
            'description'        => ['nullable', 'string'],
            'image_url'          => ['nullable', 'string'],
            'extracurricular_id' => ['nullable', 'integer', 'exists:extracurriculars,id'],
        ]);

        $achievement = Achievement::create($data);
        $this->logger->log($request->user()->id, 'created', 'achievements');

        return response()->json(['message' => 'Achievement created.', 'achievement' => $achievement], 201);
    }

    public function show(Achievement $achievement): JsonResponse
    {
        return response()->json($achievement->load('extracurricular'));
    }

    public function update(Request $request, Achievement $achievement): JsonResponse
    {
        $data = $request->validate([
            'title'              => ['sometimes', 'string', 'max:255'],
            'description'        => ['nullable', 'string'],
            'image_url'          => ['nullable', 'string'],
            'extracurricular_id' => ['nullable', 'integer', 'exists:extracurriculars,id'],
        ]);

        $achievement->update($data);
        $this->logger->log($request->user()->id, 'updated', 'achievements');

        return response()->json(['message' => 'Achievement updated.', 'achievement' => $achievement]);
    }

    public function destroy(Request $request, Achievement $achievement): JsonResponse
    {
        $this->logger->log($request->user()->id, 'deleted', 'achievements');
        $achievement->delete();

        return response()->json(['message' => 'Achievement deleted.']);
    }
}
