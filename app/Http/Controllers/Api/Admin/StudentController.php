<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportStudentCsvRequest;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\ClassRoom;
use App\Models\Program;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentController extends Controller
{
    public function __construct(private ActivityLogService $logger) {}

    public function index(Request $request): JsonResponse
    {
        $students = Student::query()
            ->with(['classRoom:id,class_name', 'user:id,name,email'])
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->string('search')->toString();
                $query->where(fn ($studentQuery) => $studentQuery
                    ->where('nis', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%"));
            })
            ->when($request->filled('class_id'), fn ($query) => $query->where('class_id', $request->integer('class_id')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->when($request->filled('account_status'), function ($query) use ($request): void {
                $request->string('account_status')->toString() === 'active'
                    ? $query->whereNotNull('user_id')
                    : $query->whereNull('user_id');
            })
            ->orderBy('name')
            ->paginate($request->integer('per_page', 15));

        return response()->json($students);
    }

    public function classes(): JsonResponse
    {
        return response()->json(ClassRoom::query()->orderBy('class_name')->get(['id', 'class_name']));
    }

    public function store(StoreStudentRequest $request): JsonResponse
    {
        $data = $request->validated();

        $student = DB::transaction(function () use ($data): Student {
            $student = Student::query()->create([
                'nis' => $data['nis'], 'name' => $data['name'], 'class_id' => $data['class_id'], 'status' => $data['status'],
            ]);

            if ($data['create_account'] ?? false) {
                $student->update(['user_id' => $this->createStudentAccount($student, $data['email'], $data['password'])->id]);
            }

            return $student;
        });

        $this->logger->log($request->user()->id, 'created', 'students', $student->id);

        return response()->json(['message' => 'Data siswa berhasil ditambahkan.', 'student' => $student->fresh(['classRoom', 'user'])], Response::HTTP_CREATED);
    }

    public function update(UpdateStudentRequest $request, Student $student): JsonResponse
    {
        $student->update($request->validated());
        $this->logger->log($request->user()->id, 'updated', 'students', $student->id);

        return response()->json(['message' => 'Data siswa berhasil diperbarui.', 'student' => $student->fresh(['classRoom', 'user'])]);
    }

    public function destroy(Request $request, Student $student): JsonResponse
    {
        $this->logger->log($request->user()->id, 'deleted', 'students', $student->id);
        $student->delete();

        return response()->json(['message' => 'Data siswa berhasil dihapus.']);
    }

    public function template(): StreamedResponse
    {
        return response()->streamDownload(function (): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['nis', 'nama', 'kelas', 'status']);
            fputcsv($handle, ['20260001', 'Nama Siswa', 'RPL 1', 'aktif']);
            fclose($handle);
        }, 'template_siswa.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function import(ImportStudentCsvRequest $request): JsonResponse
    {
        $handle = fopen($request->file('file')->getRealPath(), 'r');
        $headers = fgetcsv($handle);
        $headers = is_array($headers) ? array_map(fn ($header) => strtolower(trim((string) $header)), $headers) : [];

        if ($headers !== ['nis', 'nama', 'kelas', 'status']) {
            fclose($handle);

            return response()->json(['message' => 'Header CSV harus berisi: nis,nama,kelas,status.'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $result = ['created' => 0, 'updated' => 0, 'accounts_created' => 0, 'skipped' => 0, 'errors' => []];

        DB::transaction(function () use ($handle, $request, &$result): void {
            $line = 1;
            while (($row = fgetcsv($handle)) !== false) {
                $line++;
                if ($row === [null] || $row === []) {
                    continue;
                }

                [$nis, $name, $className, $status] = array_pad($row, 4, '');
                $nis = trim((string) $nis);
                $name = trim((string) $name);
                $className = trim((string) $className);
                $status = strtolower(trim((string) $status));

                if ($nis === '' || $name === '' || $className === '' || ! in_array($status, ['aktif', 'lulus'], true)) {
                    $result['skipped']++;
                    $result['errors'][] = "Baris {$line}: data wajib tidak lengkap atau status tidak valid.";

                    continue;
                }

                $classRoom = $this->findOrCreateClassRoom($className);
                $student = Student::query()->where('nis', $nis)->first();

                if ($student) {
                    $student->update(['name' => $name, 'class_id' => $classRoom->id, 'status' => $status]);
                    $result['updated']++;
                } else {
                    $student = Student::query()->create(['nis' => $nis, 'name' => $name, 'class_id' => $classRoom->id, 'status' => $status]);
                    $result['created']++;
                }

                if ($request->boolean('create_accounts') && $student->user_id === null) {
                    $email = strtolower($nis).'@siswa.smk.sch.id';
                    if (User::query()->where('email', $email)->doesntExist()) {
                        $student->update(['user_id' => $this->createStudentAccount($student, $email, $nis)->id]);
                        $result['accounts_created']++;
                    }
                }
            }
        });

        fclose($handle);
        $this->logger->log($request->user()->id, 'imported', 'students');

        return response()->json(['message' => 'Import data siswa selesai.', 'result' => $result]);
    }

    private function createStudentAccount(Student $student, string $email, string $password): User
    {
        $user = User::query()->create(['name' => $student->name, 'email' => $email, 'password' => Hash::make($password)]);
        $studentRole = Role::query()->where('role_name', 'Siswa')->firstOrFail();
        $user->roles()->sync([$studentRole->id]);

        return $user;
    }

    private function findOrCreateClassRoom(string $className): ClassRoom
    {
        $existingClassRoom = ClassRoom::query()->where('class_name', $className)->first();
        if ($existingClassRoom) {
            return $existingClassRoom;
        }

        $programName = explode(' ', $className)[0];
        $program = Program::query()->firstOrCreate(['program_name' => $programName]);

        return ClassRoom::query()->create(['class_name' => $className, 'program_id' => $program->id]);
    }
}
