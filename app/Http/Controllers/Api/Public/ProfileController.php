<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Program;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    /**
     * GET /api/public/profile
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'school'     => SiteSetting::whereIn('group', ['school', 'general'])->pluck('value', 'key'),
            'programs'   => Program::with('classes')->get(),
            'facilities' => Facility::with('program')->get(),
        ]);
    }
}
