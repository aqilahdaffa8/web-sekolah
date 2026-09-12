<?php

namespace Tests\Feature;

use App\Models\ClassRoom;
use App\Models\Program;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminStudentManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;

    private ClassRoom $classRoom;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $this->superAdmin = User::factory()->create();
        $this->superAdmin->roles()->attach(Role::query()->where('role_name', 'Super Admin')->firstOrFail());
        $this->classRoom = ClassRoom::query()->create(['class_name' => 'RPL 1', 'program_id' => Program::query()->create(['program_name' => 'RPL'])->id]);
    }

    public function test_super_admin_can_list_students(): void
    {
        Student::factory()->create(['class_id' => $this->classRoom->id]);

        $this->actingAs($this->superAdmin, 'sanctum')->getJson('/api/admin/students')
            ->assertOk()->assertJsonCount(1, 'data');
    }

    public function test_super_admin_can_create_student_with_login_account(): void
    {
        $this->actingAs($this->superAdmin, 'sanctum')->postJson('/api/admin/students', [
            'nis' => '20260001', 'name' => 'Siti Nurhaliza', 'class_id' => $this->classRoom->id, 'status' => 'aktif',
            'create_account' => true, 'email' => 'siti@siswa.smk.sch.id', 'password' => 'password123',
        ])->assertCreated()->assertJsonPath('student.nis', '20260001');

        $student = Student::query()->where('nis', '20260001')->firstOrFail();
        $this->assertNotNull($student->user_id);
        $this->assertTrue($student->user->hasRole('Siswa'));
    }

    public function test_super_admin_can_import_students_and_create_accounts(): void
    {
        $file = UploadedFile::fake()->createWithContent('siswa.csv', "nis,nama,kelas,status\n20260002,Budi Santoso,RPL 2,aktif\n");

        $this->actingAs($this->superAdmin, 'sanctum')->post('/api/admin/students/import', [
            'file' => $file, 'create_accounts' => true,
        ])->assertOk()->assertJsonPath('result.created', 1)->assertJsonPath('result.accounts_created', 1);

        $this->assertDatabaseHas('classes', ['class_name' => 'RPL 2']);
        $this->assertDatabaseHas('students', ['nis' => '20260002', 'name' => 'Budi Santoso']);
        $this->assertDatabaseHas('users', ['email' => '20260002@siswa.smk.sch.id']);
    }

    public function test_non_admin_is_forbidden_from_student_management(): void
    {
        $teacher = User::factory()->create();
        $teacher->roles()->attach(Role::query()->where('role_name', 'Guru')->firstOrFail());

        $this->actingAs($teacher, 'sanctum')->getJson('/api/admin/students')->assertForbidden();
    }
}
