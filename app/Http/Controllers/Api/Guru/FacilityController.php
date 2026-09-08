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
        $facilityName = $request->input('facility_name') ?? $request->input('name');
        if (! $facilityName) {
            return response()->json(['message' => 'The facility name field is required.', 'errors' => ['name' => ['Nama fasilitas wajib diisi.']]], 422);
        }

        $imageUrl = $request->input('image_url') ?? $request->input('image');
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('facilities', 'public');
            $imageUrl = '/storage/' . $path;
        }

        $data = [
            'facility_name' => $facilityName,
            'image_url'     => $imageUrl,
            'program_id'    => $request->input('program_id') ?? (\App\Models\Program::first()?->id ?? 1),
        ];

        if (\Illuminate\Support\Facades\Schema::hasColumn('facilities', 'description')) {
            $data['description'] = $request->input('description');
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('facilities', 'location')) {
            $data['location'] = $request->input('location');
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('facilities', 'capacity')) {
            $data['capacity'] = $request->input('capacity') ? (int) $request->input('capacity') : null;
        }

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
        $updateData = [];

        if ($request->filled('facility_name') || $request->filled('name')) {
            $updateData['facility_name'] = $request->input('facility_name') ?? $request->input('name');
        }
        if ($request->has('description')) {
            $updateData['description'] = $request->input('description');
        }
        if ($request->has('location')) {
            $updateData['location'] = $request->input('location');
        }
        if ($request->has('capacity')) {
            $updateData['capacity'] = $request->input('capacity');
        }
        if ($request->has('program_id')) {
            $updateData['program_id'] = $request->input('program_id');
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('facilities', 'public');
            $updateData['image_url'] = '/storage/' . $path;
        } elseif ($request->filled('image_url') || $request->filled('image')) {
            $updateData['image_url'] = $request->input('image_url') ?? $request->input('image');
        }

        $facility->update($updateData);
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
