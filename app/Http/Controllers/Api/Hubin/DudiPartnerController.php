<?php

namespace App\Http\Controllers\Api\Hubin;

use App\Http\Controllers\Controller;
use App\Models\DudiPartner;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DudiPartnerController extends Controller
{
    public function __construct(private ActivityLogService $logger) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json(
            DudiPartner::when($request->search, fn ($q) => $q->where('company_name', 'like', "%{$request->search}%"))
                ->paginate(15)
        );
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'company_name'   => ['required', 'string', 'max:255'],
            'industry_field' => ['nullable', 'string'],
            'logo_url'       => ['nullable', 'string'],
            'mou_document'   => ['nullable', 'string'],
        ]);

        $partner = DudiPartner::create($data);
        $this->logger->log($request->user()->id, 'created', 'dudi_partners');

        return response()->json(['message' => 'DUDI partner created.', 'partner' => $partner], 201);
    }

    public function show(DudiPartner $dudiPartner): JsonResponse
    {
        return response()->json($dudiPartner->load('jobVacancies'));
    }

    public function update(Request $request, DudiPartner $dudiPartner): JsonResponse
    {
        $data = $request->validate([
            'company_name'   => ['sometimes', 'string', 'max:255'],
            'industry_field' => ['nullable', 'string'],
            'logo_url'       => ['nullable', 'string'],
            'mou_document'   => ['nullable', 'string'],
        ]);

        $dudiPartner->update($data);
        $this->logger->log($request->user()->id, 'updated', 'dudi_partners');

        return response()->json(['message' => 'DUDI partner updated.', 'partner' => $dudiPartner]);
    }

    public function destroy(Request $request, DudiPartner $dudiPartner): JsonResponse
    {
        $this->logger->log($request->user()->id, 'deleted', 'dudi_partners');
        $dudiPartner->delete();

        return response()->json(['message' => 'DUDI partner deleted.']);
    }
}
