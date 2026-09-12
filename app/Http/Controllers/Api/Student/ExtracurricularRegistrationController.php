<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentExtracurricularRegistrationRequest;
use App\Models\ExtracurricularRegistration;
use Illuminate\Http\JsonResponse;

class ExtracurricularRegistrationController extends Controller
{
    /**
     * POST /api/extracurricular-registrations
     *
     * Pendaftaran eskul hanya dari akun Siswa yang terhubung ke NIS aktif.
     * Nama dan NIS tidak diterima dari input klien.
     */
    public function store(StudentExtracurricularRegistrationRequest $request): JsonResponse
    {
        $user = $request->user()->loadMissing('student');
        $student = $user->student;

        if (! $student || ! $student->isActive()) {
            return response()->json([
                'message' => 'Pendaftaran ekstrakurikuler hanya untuk siswa aktif.',
                'code' => 403,
            ], 403);
        }

        $data = $request->validated();

        $alreadyRegistered = ExtracurricularRegistration::query()
            ->where('student_id', $student->id)
            ->where('extracurricular_id', $data['extracurricular_id'])
            ->exists();

        if ($alreadyRegistered) {
            return response()->json([
                'message' => 'Anda sudah terdaftar pada ekstrakurikuler ini.',
                'errors' => [
                    'extracurricular_id' => ['Pendaftaran ganda tidak diizinkan untuk ekstrakurikuler yang sama.'],
                ],
            ], 422);
        }

        $attributes = [
            'student_id' => $student->id,
            'extracurricular_id' => $data['extracurricular_id'],
            'status' => 'pending',
        ];

        if (array_key_exists('notes', $data)) {
            $attributes['notes'] = $data['notes'];
        }

        $registration = ExtracurricularRegistration::create($attributes);

        return response()->json([
            'message' => 'Pendaftaran berhasil dan menunggu validasi admin.',
            'registration' => $registration->load(['student', 'extracurricular']),
        ], 201);
    }
}
