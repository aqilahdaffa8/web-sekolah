<?php

namespace App\Http\Controllers\Api\Guru;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function __construct(private ActivityLogService $logger) {}

    public function index(): JsonResponse
    {
        return response()->json(Facility::with('program')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image'       => ['nullable', 'string'],
            'program_id'  => ['nullable', 'integer', 'exists:programs,id'],
        ]);

        $facility = Facility::create($data);
        $this->logger->log($request->user()->id, 'created', 'facilities', $facility->id);

        return response()->json(['message' => 'Facility created.', 'facility' => $facility], 201);
    }

    public function show(Facility $facility): JsonResponse
    {
        return response()->json($facility->load('program'));
    }

    public function update(Request $request, Facility $facility): JsonResponse
    {
        $data = $request->validate([
            'name'        => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image'       => ['nullable', 'string'],
            'program_id'  => ['nullable', 'integer', 'exists:programs,id'],
        ]);

        $facility->update($data);
        $this->logger->log($request->user()->id, 'updated', 'facilities', $facility->id);

        return response()->json(['message' => 'Facility updated.', 'facility' => $facility]);
    }

    public function destroy(Request $request, Facility $facility): JsonResponse
    {
        $this->logger->log($request->user()->id, 'deleted', 'facilities', $facility->id);
        $facility->delete();

        return response()->json(['message' => 'Facility deleted.']);
    }
}
