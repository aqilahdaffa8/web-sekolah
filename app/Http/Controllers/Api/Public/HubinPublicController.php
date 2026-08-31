<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\DudiPartner;
use App\Models\JobVacancy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HubinPublicController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'partners' => DudiPartner::when($request->search, fn ($q) => $q->where('company_name', 'like', "%{$request->search}%"))
                ->paginate(12),
            'vacancies' => JobVacancy::with('dudiPartner')
                ->where('status', 'open')
                ->latest()
                ->paginate(10),
        ]);
    }
}
