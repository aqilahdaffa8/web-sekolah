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
        $status = $request->query('status');
        $search = $request->query('search');
        $eskulId = $request->query('extracurricular_id');

        $query = ExtracurricularRegistration::with(['extracurricular', 'student.classRoom'])
            ->when($status !== null && $status !== '', fn ($q) => $q->where('status', $status))
            ->when($eskulId !== null && $eskulId !== '', fn ($q) => $q->where('extracurricular_id', $eskulId))
            ->when($search !== null && $search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->whereHas('student', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%")
                           ->orWhere('nis', 'like', "%{$search}%");
                    })->orWhereHas('extracurricular', function ($eq) use ($search) {
                        $eq->where('name', 'like', "%{$search}%");
                    });
                });
            })
            ->latest();

        $paginated = $query->paginate($request->query('per_page', 15));

        $counts = [
            'all'      => ExtracurricularRegistration::count(),
            'pending'  => ExtracurricularRegistration::where('status', 'pending')->count(),
            'approved' => ExtracurricularRegistration::where('status', 'approved')->count(),
            'rejected' => ExtracurricularRegistration::where('status', 'rejected')->count(),
        ];

        return response()->json([
            'data'         => $paginated->items(),
            'current_page' => $paginated->currentPage(),
            'last_page'    => $paginated->lastPage(),
            'per_page'     => $paginated->perPage(),
            'total'        => $paginated->total(),
            'from'         => $paginated->firstItem() ?? 0,
            'to'           => $paginated->lastItem() ?? 0,
            'counts'       => $counts,
        ]);
    }

    public function updateStatus(Request $request, ExtracurricularRegistration $registration): JsonResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:approved,rejected,pending'],
            'notes' => ['nullable', 'string'],
        ]);

        $updateData = ['status' => $data['status']];
        if (\Illuminate\Support\Facades\Schema::hasColumn('extracurricular_registrations', 'notes') && array_key_exists('notes', $data)) {
            $updateData['notes'] = $data['notes'];
        }

        $registration->update($updateData);
        $this->logger->log(
            $request->user()->id,
            "registration_{$data['status']}",
            'extracurricular_registrations',
            $registration->id
        );

        return response()->json(['message' => "Registration {$data['status']}.", 'registration' => $registration]);
    }
}
