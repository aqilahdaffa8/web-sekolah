<?php

namespace Tests\Feature;

use App\Models\ClassRoom;
use App\Models\Program;
use App\Models\Role;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeacherClassSubject;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Validates the teacher-class-subject authorization in grade input:
 *  - Teacher can input grades for their assigned class + subject
 *  - Teacher is blocked from inputting grades for other class/subject combos
 *  - Score must be 0–100
 */
class StudentGradeTest extends TestCase
{
    use RefreshDatabase;

    private User $teacher;

    private Student $student;

    private Subject $subject;

    private ClassRoom $classRoom;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);

        $program = Program::factory()->create(['program_name' => 'RPL']);
        $this->classRoom = ClassRoom::factory()->create(['program_id' => $program->id, 'class_name' => 'RPL 1']);
        $this->subject = Subject::factory()->create(['subject_name' => 'Pemrograman Web']);
        $this->student = Student::factory()->create(['class_id' => $this->classRoom->id]);
        $this->teacher = User::factory()->create();

        $this->teacher->roles()->attach(Role::where('role_name', 'Guru')->first());

        // Assign teacher to the class + subject
        TeacherClassSubject::create([
            'teacher_id' => $this->teacher->id,
            'class_id' => $this->classRoom->id,
            'subject_id' => $this->subject->id,
        ]);
    }

    /** Teacher can grade a student in their assigned class/subject */
    public function test_teacher_can_grade_assigned_student(): void
    {
        $response = $this->actingAs($this->teacher, 'sanctum')
            ->postJson('/api/guru/grades', [
                'student_id' => $this->student->id,
                'subject_id' => $this->subject->id,
                'theory_score' => 85.0,
            ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['theory_score' => 85.0]);
    }

    /** Teacher is blocked from grading a student in an unassigned subject */
    public function test_teacher_cannot_grade_unassigned_subject(): void
    {
        $otherSubject = Subject::factory()->create(['subject_name' => 'Matematika']);

        $response = $this->actingAs($this->teacher, 'sanctum')
            ->postJson('/api/guru/grades', [
                'student_id' => $this->student->id,
                'subject_id' => $otherSubject->id,
                'theory_score' => 90.0,
            ]);

        $response->assertStatus(422);
    }

    /** Score > 100 should be rejected */
    public function test_score_over_100_is_rejected(): void
    {
        $response = $this->actingAs($this->teacher, 'sanctum')
            ->postJson('/api/guru/grades', [
                'student_id' => $this->student->id,
                'subject_id' => $this->subject->id,
                'theory_score' => 105.0,
            ]);

        $response->assertStatus(422);
    }

    /** Score below 0 should be rejected */
    public function test_negative_score_is_rejected(): void
    {
        $response = $this->actingAs($this->teacher, 'sanctum')
            ->postJson('/api/guru/grades', [
                'student_id' => $this->student->id,
                'subject_id' => $this->subject->id,
                'theory_score' => -5.0,
            ]);

        $response->assertStatus(422);
    }
}
