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
     * Throws 422 ValidationException if not authorized.
     */
    public function authorizeTeacher(User $teacher, int $studentId, int $subjectId): void
    {
        $student = Student::with('classRoom')->findOrFail($studentId);

        $assigned = TeacherClassSubject::where('teacher_id', $teacher->id)
            ->where('class_id', $student->class_id)
            ->where('subject_id', $subjectId)
            ->exists();

        if (! $assigned) {
            throw ValidationException::withMessages([
                'subject_id' => [
                    'You are not assigned to teach this subject in the student\'s class.',
                ],
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
                    $field => ["The {$field} must be between 0 and 100."],
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

        $scores = array_filter([
            'theory_score' => $data['theory_score'] ?? null,
            'practice_score' => $data['practice_score'] ?? null,
            'ukk_score' => $data['ukk_score'] ?? null,
            'pkl_score' => $data['pkl_score'] ?? null,
        ], fn ($v) => $v !== null);

        $this->validateScores($scores);

        return StudentGrade::updateOrCreate(
            ['student_id' => $data['student_id'], 'subject_id' => $data['subject_id']],
            array_merge($scores, ['teacher_id' => $teacher->id])
        );
    }
}
