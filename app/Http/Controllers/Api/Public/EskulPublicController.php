<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\Extracurricular;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EskulPublicController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Extracurricular::with('coach')->withCount('registrations')->get()
        );
    }

    public function achievements(Request $request): JsonResponse
    {
        return response()->json(
            Achievement::with('extracurricular')
                ->when($request->extracurricular_id, fn ($q) => $q->where('extracurricular_id', $request->extracurricular_id))
                ->latest()
                ->paginate(12)
                ->through(fn (Achievement $achievement): array => [
                    'id' => $achievement->id,
                    'title' => $achievement->title,
                    'description' => $achievement->description,
                    'image' => $achievement->image_url,
                    'year' => $achievement->created_at?->year,
                    'level' => 'Nasional',
                    'extracurricular' => $achievement->extracurricular,
                ])
        );
    }
}
