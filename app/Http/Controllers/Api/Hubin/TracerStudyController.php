<?php

namespace App\Http\Controllers\Api\Hubin;

use App\Http\Controllers\Controller;
use App\Models\TracerStudy;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TracerStudyController extends Controller
{
    public function __construct(private ActivityLogService $logger) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json(
            TracerStudy::with('student')
                ->when($request->graduation_year, fn ($q) => $q->where('graduation_year', $request->graduation_year))
                ->when($request->current_status, fn ($q) => $q->where('current_status', $request->current_status))
                ->paginate(15)
        );
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'student_id'             => ['required', 'integer', 'exists:students,id'],
            'graduation_year'        => ['required', 'integer', 'min:2000', 'max:2100'],
            'current_status'         => ['required', 'in:Kerja,Kuliah,Wirausaha'],
            'company_or_campus_name' => ['nullable', 'string'],
        ]);

        $study = TracerStudy::create($data);
        $this->logger->log($request->user()->id, 'created', 'tracer_studies');

        return response()->json(['message' => 'Tracer study recorded.', 'study' => $study->load('student')], 201);
    }

    public function show(TracerStudy $tracerStudy): JsonResponse
    {
        return response()->json($tracerStudy->load('student'));
    }

    public function update(Request $request, TracerStudy $tracerStudy): JsonResponse
    {
        $data = $request->validate([
            'graduation_year'        => ['sometimes', 'integer'],
            'current_status'         => ['sometimes', 'in:Kerja,Kuliah,Wirausaha'],
            'company_or_campus_name' => ['nullable', 'string'],
        ]);

        $tracerStudy->update($data);
        $this->logger->log($request->user()->id, 'updated', 'tracer_studies');

        return response()->json(['message' => 'Tracer study updated.', 'study' => $tracerStudy]);
    }

    public function destroy(Request $request, TracerStudy $tracerStudy): JsonResponse
    {
        $this->logger->log($request->user()->id, 'deleted', 'tracer_studies');
        $tracerStudy->delete();

        return response()->json(['message' => 'Tracer study deleted.']);
    }
}
