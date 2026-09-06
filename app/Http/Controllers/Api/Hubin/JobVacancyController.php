<?php

namespace App\Http\Controllers\Api\Hubin;

use App\Http\Controllers\Controller;
use App\Models\JobVacancy;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobVacancyController extends Controller
{
    public function __construct(private ActivityLogService $logger) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json(
            JobVacancy::with('dudiPartner')
                ->when($request->status, fn ($q) => $q->where('status', $request->status))
                ->latest()
                ->paginate(15)
        );
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'dudi_id' => ['required', 'integer', 'exists:dudi_partners,id'],
            'job_title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['in:open,closed'],
        ]);

        $vacancy = JobVacancy::create($data);
        $this->logger->log($request->user()->id, 'created', 'job_vacancies');

        return response()->json(['message' => 'Job vacancy created.', 'vacancy' => $vacancy->load('dudiPartner')], 201);
    }

    public function show(JobVacancy $jobVacancy): JsonResponse
    {
        return response()->json($jobVacancy->load('dudiPartner'));
    }

    public function update(Request $request, JobVacancy $jobVacancy): JsonResponse
    {
        $data = $request->validate([
            'dudi_id' => ['sometimes', 'integer', 'exists:dudi_partners,id'],
            'job_title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['in:open,closed'],
        ]);

        $jobVacancy->update($data);
        $this->logger->log($request->user()->id, 'updated', 'job_vacancies');

        return response()->json(['message' => 'Job vacancy updated.', 'vacancy' => $jobVacancy]);
    }

    public function destroy(Request $request, JobVacancy $jobVacancy): JsonResponse
    {
        $this->logger->log($request->user()->id, 'deleted', 'job_vacancies');
        $jobVacancy->delete();

        return response()->json(['message' => 'Job vacancy deleted.']);
    }
}
