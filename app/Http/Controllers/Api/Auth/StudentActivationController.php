<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActivateStudentAccountRequest;
use App\Http\Requests\CheckStudentNisRequest;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentActivationController extends Controller
{
    /**
     * POST /api/auth/student/check-nis
     *
     * Periksa ketersediaan dan eligibilitas NIS untuk aktivasi akun mandiri.
     */
    public function checkNis(CheckStudentNisRequest $request): JsonResponse
    {
        $student = Student::with('classRoom')->where('nis', $request->nis)->first();

        if (! $student) {
            return response()->json([
                'message' => 'NIS tidak ditemukan dalam basis data siswa sekolah.',
            ], 404);
        }

        if (! $student->isActive()) {
            return response()->json([
                'message' => 'Hanya siswa berstatus aktif yang dapat melakukan aktivasi akun.',
            ], 422);
        }

        if ($student->user_id !== null) {
            return response()->json([
                'message' => 'Akun untuk NIS ini sudah aktif. Silakan langsung masuk di halaman login.',
            ], 422);
        }

        return response()->json([
            'message' => 'Data siswa ditemukan.',
            'student' => [
                'id' => $student->id,
                'nis' => $student->nis,
                'name' => $student->name,
                'class_name' => $student->classRoom?->class_name ?? 'Kelas Belum Ditentukan',
            ],
        ]);
    }

    /**
     * POST /api/auth/student/activate
     *
     * Buat akun login baru yang terhubung ke data siswa ber-NIS aktif.
     */
    public function activate(ActivateStudentAccountRequest $request): JsonResponse
    {
        $data = $request->validated();

        return DB::transaction(function () use ($data, $request): JsonResponse {
            $student = Student::where('nis', $data['nis'])->lockForUpdate()->first();

            if (! $student) {
                return response()->json([
                    'message' => 'NIS tidak ditemukan dalam basis data siswa sekolah.',
                ], 404);
            }

            if (! $student->isActive()) {
                return response()->json([
                    'message' => 'Hanya siswa berstatus aktif yang dapat melakukan aktivasi akun.',
                ], 422);
            }

            if ($student->user_id !== null) {
                return response()->json([
                    'message' => 'Akun untuk NIS ini sudah aktif. Silakan langsung masuk di halaman login.',
                ], 422);
            }

            $user = User::create([
                'name' => $student->name,
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $role = Role::where('role_name', 'Siswa')->first();
            if ($role) {
                $user->roles()->sync([$role->id]);
            }

            $student->update(['user_id' => $user->id]);

            $token = $user->createToken($request->input('device_name', 'web'))->plainTextToken;

            return response()->json([
                'message' => 'Aktivasi akun berhasil! Akun Anda telah aktif.',
                'token' => $token,
                'user' => $user->toSessionArray(),
            ], 201);
        });
    }
}
