<?php

namespace Tests\Feature;

use App\Models\ClassRoom;
use App\Models\Program;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentActivationTest extends TestCase
{
    use RefreshDatabase;

    private Student $eligibleStudent;

    private ClassRoom $classRoom;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);

        $program = Program::query()->create(['program_name' => 'Rekayasa Perangkat Lunak']);
        $this->classRoom = ClassRoom::query()->create([
            'class_name' => 'RPL 1',
            'program_id' => $program->id,
        ]);

        $this->eligibleStudent = Student::factory()->create([
            'user_id' => null,
            'nis' => '20240010',
            'name' => 'Dimas Saputra',
            'class_id' => $this->classRoom->id,
            'status' => 'aktif',
        ]);
    }

    public function test_check_nis_success_for_eligible_student(): void
    {
        $response = $this->postJson('/api/auth/student/check-nis', [
            'nis' => '20240010',
        ]);

        $response->assertOk()
            ->assertJsonPath('student.nis', '20240010')
            ->assertJsonPath('student.name', 'Dimas Saputra')
            ->assertJsonPath('student.class_name', 'RPL 1');
    }

    public function test_check_nis_fails_if_not_found(): void
    {
        $response = $this->postJson('/api/auth/student/check-nis', [
            'nis' => '99999999',
        ]);

        $response->assertNotFound()
            ->assertJsonFragment(['message' => 'NIS tidak ditemukan dalam basis data siswa sekolah.']);
    }

    public function test_check_nis_fails_if_student_is_inactive(): void
    {
        $this->eligibleStudent->update(['status' => 'lulus']);

        $response = $this->postJson('/api/auth/student/check-nis', [
            'nis' => '20240010',
        ]);

        $response->assertUnprocessable()
            ->assertJsonFragment(['message' => 'Hanya siswa berstatus aktif yang dapat melakukan aktivasi akun.']);
    }

    public function test_check_nis_fails_if_already_activated(): void
    {
        $user = User::query()->create([
            'name' => 'Dimas Saputra',
            'email' => 'dimas@siswa.smk.sch.id',
            'password' => 'password123',
        ]);
        $this->eligibleStudent->update(['user_id' => $user->id]);

        $response = $this->postJson('/api/auth/student/check-nis', [
            'nis' => '20240010',
        ]);

        $response->assertUnprocessable()
            ->assertJsonFragment(['message' => 'Akun untuk NIS ini sudah aktif. Silakan langsung masuk di halaman login.']);
    }

    public function test_student_can_successfully_activate_account(): void
    {
        $response = $this->postJson('/api/auth/student/activate', [
            'nis' => '20240010',
            'email' => 'dimas.baru@siswa.smk.sch.id',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ]);

        $response->assertCreated()
            ->assertJsonStructure([
                'message',
                'token',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'roles',
                    'student' => ['id', 'nis', 'name', 'status'],
                ],
            ])
            ->assertJsonPath('user.name', 'Dimas Saputra')
            ->assertJsonPath('user.email', 'dimas.baru@siswa.smk.sch.id')
            ->assertJsonPath('user.student.nis', '20240010');

        $this->assertDatabaseHas('users', [
            'name' => 'Dimas Saputra',
            'email' => 'dimas.baru@siswa.smk.sch.id',
        ]);

        $newUser = User::query()->where('email', 'dimas.baru@siswa.smk.sch.id')->first();
        $this->assertNotNull($newUser);
        $this->assertTrue($newUser->hasRole('Siswa'));

        $this->assertDatabaseHas('students', [
            'id' => $this->eligibleStudent->id,
            'user_id' => $newUser->id,
        ]);
    }

    public function test_activation_fails_with_duplicate_email(): void
    {
        User::query()->create([
            'name' => 'Existing User',
            'email' => 'used@smk.sch.id',
            'password' => 'password123',
        ]);

        $response = $this->postJson('/api/auth/student/activate', [
            'nis' => '20240010',
            'email' => 'used@smk.sch.id',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);

        $this->assertNull($this->eligibleStudent->fresh()->user_id);
    }

    public function test_activation_fails_with_short_or_mismatched_password(): void
    {
        $response = $this->postJson('/api/auth/student/activate', [
            'nis' => '20240010',
            'email' => 'dimas@smk.sch.id',
            'password' => 'pendek',
            'password_confirmation' => 'berbeda',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['password']);
    }

    public function test_activation_fails_if_student_already_has_account(): void
    {
        $user = User::query()->create([
            'name' => 'Sudah Aktif',
            'email' => 'lama@siswa.smk.sch.id',
            'password' => 'password123',
        ]);
        $this->eligibleStudent->update(['user_id' => $user->id]);

        $response = $this->postJson('/api/auth/student/activate', [
            'nis' => '20240010',
            'email' => 'baru@siswa.smk.sch.id',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ]);

        $response->assertUnprocessable()
            ->assertJsonFragment(['message' => 'Akun untuk NIS ini sudah aktif. Silakan langsung masuk di halaman login.']);
    }
}
