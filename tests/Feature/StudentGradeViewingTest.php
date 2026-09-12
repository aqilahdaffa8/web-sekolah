<?php

namespace Tests\Feature;

use App\Models\ClassRoom;
use App\Models\Program;
use App\Models\Role;
use App\Models\Student;
use App\Models\StudentGrade;
use App\Models\Subject;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentGradeViewingTest extends TestCase
{
    use RefreshDatabase;

    private Student $student;

    private User $studentUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $program = Program::factory()->create(['program_name' => 'RPL']);
        $classRoom = ClassRoom::factory()->create(['class_name' => 'RPL 1', 'program_id' => $program->id]);
        $this->studentUser = User::factory()->create(['name' => 'Ayu Lestari']);
        $this->studentUser->roles()->attach(Role::query()->where('role_name', 'Siswa')->firstOrFail());
        $this->student = Student::factory()->create(['user_id' => $this->studentUser->id, 'name' => 'Ayu Lestari', 'nis' => '20260010', 'class_id' => $classRoom->id]);

        StudentGrade::query()->create([
            'student_id' => $this->student->id,
            'subject_id' => Subject::factory()->create(['subject_name' => 'Matematika'])->id,
            'theory_score' => 80,
            'practice_score' => 90,
        ]);
    }

    public function test_student_can_view_own_grades_after_identity_verification(): void
    {
        $this->actingAs($this->studentUser, 'sanctum')->postJson('/api/student/grades', [
            'name' => 'Ayu Lestari', 'nis' => '20260010',
        ])->assertOk()
            ->assertJsonPath('student.nis', '20260010')
            ->assertJsonPath('grades.0.subject', 'Matematika')
            ->assertJsonPath('grades.0.final_score', 85.0);
    }

    public function test_student_cannot_view_grades_with_another_students_identity(): void
    {
        $this->actingAs($this->studentUser, 'sanctum')->postJson('/api/student/grades', [
            'name' => 'Siswa Lain', 'nis' => '20269999',
        ])->assertNotFound();
    }

    public function test_student_can_download_own_grades_as_pdf(): void
    {
        $this->actingAs($this->studentUser, 'sanctum')->post('/api/student/grades/download', [
            'name' => 'Ayu Lestari', 'nis' => '20260010',
        ])->assertOk()
            ->assertHeader('content-type', 'application/pdf')
            ->assertHeader('content-disposition', 'attachment; filename="nilai-20260010.pdf"');
    }

    public function test_guest_cannot_access_student_grades(): void
    {
        $this->postJson('/api/student/grades', ['name' => 'Ayu Lestari', 'nis' => '20260010'])->assertUnauthorized();

    }
}
