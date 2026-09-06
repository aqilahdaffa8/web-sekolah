<?php

namespace App\Http\Controllers\Api\Eskul;

use App\Http\Controllers\Controller;
use App\Models\Extracurricular;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExtracurricularController extends Controller
{
    public function __construct(private ActivityLogService $logger) {}

    public function index(): JsonResponse
    {
        return response()->json(Extracurricular::with('coach')->withCount('registrations')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'schedule' => ['nullable', 'string'],
            'coach_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $eskul = Extracurricular::create($data);
        $this->logger->log($request->user()->id, 'created', 'extracurriculars');

        return response()->json(['message' => 'Extracurricular created.', 'extracurricular' => $eskul], 201);
    }

    public function show(Extracurricular $extracurricular): JsonResponse
    {
        return response()->json($extracurricular->load('registrations.student', 'coach'));
    }

    public function update(Request $request, Extracurricular $extracurricular): JsonResponse
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'schedule' => ['nullable', 'string'],
            'coach_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $extracurricular->update($data);
        $this->logger->log($request->user()->id, 'updated', 'extracurriculars');

        return response()->json(['message' => 'Extracurricular updated.', 'extracurricular' => $extracurricular]);
    }

    public function destroy(Request $request, Extracurricular $extracurricular): JsonResponse
    {
        $this->logger->log($request->user()->id, 'deleted', 'extracurriculars');
        $extracurricular->delete();

        return response()->json(['message' => 'Extracurricular deleted.']);
    }
}
