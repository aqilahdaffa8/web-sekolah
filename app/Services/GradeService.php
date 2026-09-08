<?php

namespace App\Services;

use App\Models\Student;
use App\Models\StudentGrade;
use App\Models\TeacherClassSubject;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class GradeService
{
    /**
     * Verify the authenticated teacher is assigned to the student's class + subject.
     * If Super Admin or not yet mapped, registers mapping automatically.
     */
    public function authorizeTeacher(User $teacher, int $studentId, int $subjectId): void
    {
        if ($teacher->hasRole('Super Admin')) {
            return;
        }

        $student = Student::with('classRoom')->findOrFail($studentId);

        $assigned = TeacherClassSubject::where('teacher_id', $teacher->id)
            ->where('class_id', $student->class_id)
            ->where('subject_id', $subjectId)
            ->exists();

        if (! $assigned) {
            TeacherClassSubject::firstOrCreate([
                'teacher_id' => $teacher->id,
                'class_id' => $student->class_id,
                'subject_id' => $subjectId,
            ]);
        }
    }

    /**
     * Validate that each score is between 0 and 100.
     */
    public function validateScores(array $scores): void
    {
        foreach ($scores as $field => $value) {
            if ($value !== null && ($value < 0 || $value > 100)) {
                throw ValidationException::withMessages([
                    $field => ["Nilai {$field} harus di antara 0 dan 100."],
                ]);
            }
        }
    }

    /**
     * Upsert a grade record after authorization & validation.
     */
    public function upsert(User $teacher, array $data): StudentGrade
    {
        $this->authorizeTeacher($teacher, $data['student_id'], $data['subject_id']);

        $scores = [
            'theory_score' => (isset($data['theory_score']) && $data['theory_score'] !== '' && $data['theory_score'] !== null) ? (float) $data['theory_score'] : null,
            'practice_score' => (isset($data['practice_score']) && $data['practice_score'] !== '' && $data['practice_score'] !== null) ? (float) $data['practice_score'] : null,
            'ukk_score' => (isset($data['ukk_score']) && $data['ukk_score'] !== '' && $data['ukk_score'] !== null) ? (float) $data['ukk_score'] : null,
            'pkl_score' => (isset($data['pkl_score']) && $data['pkl_score'] !== '' && $data['pkl_score'] !== null) ? (float) $data['pkl_score'] : null,
        ];

        $this->validateScores($scores);

        return StudentGrade::updateOrCreate(
            ['student_id' => $data['student_id'], 'subject_id' => $data['subject_id']],
            array_merge($scores, ['teacher_id' => $teacher->id])
        );
    }
}
