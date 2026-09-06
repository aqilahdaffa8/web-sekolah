<?php

namespace App\Http\Controllers\Api\Eskul;

use App\Http\Controllers\Controller;
use App\Models\ExtracurricularRegistration;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function __construct(private ActivityLogService $logger) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json(
            ExtracurricularRegistration::with(['extracurricular', 'student'])
                ->when($request->status, fn ($q) => $q->where('status', $request->status))
                ->when($request->extracurricular_id, fn ($q) => $q->where('extracurricular_id', $request->extracurricular_id))
                ->latest()
                ->paginate(15)
        );
    }

    public function updateStatus(Request $request, ExtracurricularRegistration $registration): JsonResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:approved,rejected'],
            'notes' => ['nullable', 'string'],
        ]);

        $registration->update($data);
        $this->logger->log(
            $request->user()->id,
            "registration_{$data['status']}",
            'extracurricular_registrations',
            $registration->id
        );

        return response()->json(['message' => "Registration {$data['status']}.", 'registration' => $registration]);
    }
}
