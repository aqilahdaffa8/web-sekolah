<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\PublicExtracurricularRegistrationRequest;
use App\Models\Achievement;
use App\Models\Extracurricular;
use App\Models\ExtracurricularRegistration;
use App\Models\Student;
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

    public function register(PublicExtracurricularRegistrationRequest $request): JsonResponse
    {
        $data = $request->validated();
        $student = Student::firstOrCreate(
            ['nis' => $data['nis']],
            [
                'name' => $data['name'],
                'class_id' => \App\Models\ClassRoom::value('id') ?? 1,
                'status' => 'aktif',
            ]
        );

        $attributes = ['status' => 'pending'];
        if (\Illuminate\Support\Facades\Schema::hasColumn('extracurricular_registrations', 'notes') && array_key_exists('notes', $data)) {
            $attributes['notes'] = $data['notes'];
        }

        $registration = ExtracurricularRegistration::firstOrCreate(
            [
                'student_id' => $student->id,
                'extracurricular_id' => $data['extracurricular_id'],
            ],
            $attributes
        );

        if (! $registration->wasRecentlyCreated) {
            return response()->json([
                'message' => 'Siswa sudah terdaftar pada ekstrakurikuler ini.',
                'errors' => ['extracurricular_id' => ['Pendaftaran untuk ekstrakurikuler ini sudah ada.']],
            ], 422);
        }

        return response()->json([
            'message' => 'Pendaftaran berhasil dan menunggu validasi admin.',
            'registration' => $registration->load(['student', 'extracurricular']),
        ], 201);
    }
}
