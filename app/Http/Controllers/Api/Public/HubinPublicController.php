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
                ->latest()
                ->get()
                ->map(fn (DudiPartner $partner): array => [
                    'id' => $partner->id,
                    'name' => $partner->company_name,
                    'industry' => $partner->industry_field,
                    'logo' => $partner->logo_url,
                    'website' => $partner->website,
                ])
                ->values(),
            'vacancies' => JobVacancy::with('dudiPartner')
                ->where('status', 'open')
                ->latest()
                ->get()
                ->map(fn (JobVacancy $vacancy): array => [
                    'id' => $vacancy->id,
                    'title' => $vacancy->job_title,
                    'company' => $vacancy->dudiPartner?->company_name,
                    'location' => $vacancy->location,
                    'status' => $vacancy->status,
                    'description' => $vacancy->description,
                    'type' => $vacancy->employment_type,
                    'deadline' => $vacancy->deadline,
                    'salary_range' => $vacancy->salary_range,
                    'apply_url' => $vacancy->application_url,
                ])
                ->values(),
        ]);
    }
}
