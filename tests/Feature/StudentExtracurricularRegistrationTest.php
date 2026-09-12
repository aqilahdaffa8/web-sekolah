<?php

namespace Tests\Feature;

use App\Models\ClassRoom;
use App\Models\Extracurricular;
use App\Models\ExtracurricularRegistration;
use App\Models\Program;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentExtracurricularRegistrationTest extends TestCase
{
    use RefreshDatabase;

    private Extracurricular $extracurricular;

    private Student $student;

    private User $siswaUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);

        $program = Program::query()->create(['program_name' => 'RPL']);
        $classRoom = ClassRoom::query()->create([
            'class_name' => 'RPL 1',
            'program_id' => $program->id,
        ]);

        $this->siswaUser = User::query()->create([
            'name' => 'Aditya Pratama',
            'email' => 'aditya@siswa.smk.sch.id',
            'password' => 'password123',
        ]);
        $this->siswaUser->roles()->attach(Role::query()->where('role_name', 'Siswa')->first());

        $this->student = Student::factory()->create([
            'user_id' => $this->siswaUser->id,
            'name' => 'Aditya Pratama',
            'nis' => '20240001',
            'class_id' => $classRoom->id,
            'status' => 'aktif',
        ]);

        $this->extracurricular = Extracurricular::query()->create([
            'name' => 'Robotik',
            'description' => 'Klub robotik',
            'schedule' => 'Jumat 15:00',
        ]);
    }

    public function test_guest_cannot_register_for_extracurricular(): void
    {
        $this->postJson('/api/extracurricular-registrations', [
            'extracurricular_id' => $this->extracurricular->id,
            'notes' => 'Saya tertarik robotik.',
        ])->assertUnauthorized();

        $this->postJson('/api/public/extracurricular-registrations', [
            'extracurricular_id' => $this->extracurricular->id,
            'nis' => '20240001',
            'name' => 'Aditya Pratama',
        ])->assertNotFound();

        $this->assertDatabaseCount('extracurricular_registrations', 0);
    }

    public function test_guru_cannot_register_for_extracurricular(): void
    {
        $guru = User::query()->create([
            'name' => 'Bapak Guru',
            'email' => 'guru-test@smk.sch.id',
            'password' => 'password123',
        ]);
        $guru->roles()->attach(Role::query()->where('role_name', 'Guru')->first());

        $this->actingAs($guru, 'sanctum')
            ->postJson('/api/extracurricular-registrations', [
                'extracurricular_id' => $this->extracurricular->id,
            ])
            ->assertForbidden();
    }

    public function test_siswa_without_linked_student_cannot_register(): void
    {
        $this->student->update(['user_id' => null]);

        $this->actingAs($this->siswaUser, 'sanctum')
            ->postJson('/api/extracurricular-registrations', [
                'extracurricular_id' => $this->extracurricular->id,
            ])
            ->assertForbidden()
            ->assertJsonFragment(['message' => 'Pendaftaran ekstrakurikuler hanya untuk siswa aktif.']);

        $this->assertDatabaseCount('extracurricular_registrations', 0);
    }

    public function test_session_payload_includes_linked_student_identity(): void
    {
        $this->actingAs($this->siswaUser, 'sanctum')
            ->getJson('/api/auth/me')
            ->assertOk()
            ->assertJsonPath('student.nis', '20240001')
            ->assertJsonPath('student.name', 'Aditya Pratama')
            ->assertJsonPath('student.status', 'aktif');
    }

    public function test_inactive_student_cannot_register(): void
    {
        $this->student->update(['status' => 'lulus']);

        $this->actingAs($this->siswaUser, 'sanctum')
            ->postJson('/api/extracurricular-registrations', [
                'extracurricular_id' => $this->extracurricular->id,
                'notes' => 'Saya alumni.',
            ])
            ->assertForbidden()
            ->assertJsonFragment(['message' => 'Pendaftaran ekstrakurikuler hanya untuk siswa aktif.']);
    }

    public function test_active_student_can_register_using_linked_nis(): void
    {
        $response = $this->actingAs($this->siswaUser, 'sanctum')
            ->postJson('/api/extracurricular-registrations', [
                'extracurricular_id' => $this->extracurricular->id,
                'notes' => 'Ingin belajar robotik.',
                'nis' => '99999999',
                'name' => 'Bukan Nama Saya',
            ]);

        $response->assertCreated()
            ->assertJsonPath('registration.student_id', $this->student->id)
            ->assertJsonPath('registration.student.nis', '20240001')
            ->assertJsonPath('registration.student.name', 'Aditya Pratama');

        $this->assertDatabaseHas('extracurricular_registrations', [
            'student_id' => $this->student->id,
            'extracurricular_id' => $this->extracurricular->id,
            'status' => 'pending',
            'notes' => 'Ingin belajar robotik.',
        ]);
    }

    public function test_duplicate_registration_is_rejected(): void
    {
        ExtracurricularRegistration::query()->create([
            'student_id' => $this->student->id,
            'extracurricular_id' => $this->extracurricular->id,
            'status' => 'pending',
        ]);

        $this->actingAs($this->siswaUser, 'sanctum')
            ->postJson('/api/extracurricular-registrations', [
                'extracurricular_id' => $this->extracurricular->id,
            ])
            ->assertUnprocessable()
            ->assertJsonFragment(['message' => 'Anda sudah terdaftar pada ekstrakurikuler ini.']);

        $this->assertDatabaseCount('extracurricular_registrations', 1);
    }
}
