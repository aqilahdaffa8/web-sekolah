<?php

namespace App\Http\Controllers\Api\Guru;

use App\Http\Controllers\Controller;
use App\Models\StudentGrade;
use App\Services\ActivityLogService;
use App\Services\GradeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function __construct(
        private GradeService $gradeService,
        private ActivityLogService $logger
    ) {}

    public function index(Request $request): JsonResponse
    {
        $teacher = $request->user();

        $query = StudentGrade::with(['student.classRoom', 'subject', 'teacher']);

        if (! $teacher->hasRole('Super Admin')) {
            $query->where('teacher_id', $teacher->id);
        }

        if ($request->filled('class_id')) {
            $query->whereHas('student', fn ($q) => $q->where('class_id', $request->class_id));
        }

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('nis', 'like', "%{$search}%"));
        }

        $paginated = $query->latest('updated_at')->paginate($request->input('per_page', 15));

        $paginated->through(function (StudentGrade $g): array {
            $validScores = array_filter([$g->practice_score, $g->theory_score, $g->ukk_score, $g->pkl_score], fn ($v) => ! is_null($v));
            $final = count($validScores) > 0 ? round(array_sum($validScores) / count($validScores), 1) : null;

            return [
                'id' => $g->id,
                'student_id' => $g->student_id,
                'student_name' => $g->student?->name ?? '-',
                'nis' => $g->student?->nis ?? '-',
                'class' => $g->student?->classRoom?->class_name ?? '-',
                'class_id' => $g->student?->class_id,
                'subject' => $g->subject?->subject_name ?? '-',
                'subject_id' => $g->subject_id,
                'practice_score' => $g->practice_score,
                'theory_score' => $g->theory_score,
                'ukk_score' => $g->ukk_score,
                'pkl_score' => $g->pkl_score,
                'task_score' => $g->practice_score,
                'uts_score' => $g->theory_score,
                'uas_score' => $g->ukk_score,
                'final_score' => $final,
                'teacher_name' => $g->teacher?->name ?? '-',
                'updated_at' => $g->updated_at?->toDateTimeString(),
            ];
        });

        return response()->json($paginated);
    }

    /**
     * Get all students in a class with their existing grade for a specific subject
     */
    public function studentsByClass(Request $request): JsonResponse
    {
        $request->validate([
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
        ]);

        $students = \App\Models\Student::where('class_id', $request->class_id)
            ->with(['grades' => function ($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            }])
            ->orderBy('name')
            ->get()
            ->map(function ($student) {
                $grade = $student->grades->first();
                $validScores = array_filter([$grade?->practice_score, $grade?->theory_score, $grade?->ukk_score, $grade?->pkl_score], fn ($v) => ! is_null($v));
                $final = count($validScores) > 0 ? round(array_sum($validScores) / count($validScores), 1) : null;

                return [
                    'student_id' => $student->id,
                    'student_name' => $student->name,
                    'nis' => $student->nis,
                    'grade_id' => $grade?->id,
                    'practice_score' => $grade?->practice_score,
                    'theory_score' => $grade?->theory_score,
                    'ukk_score' => $grade?->ukk_score,
                    'pkl_score' => $grade?->pkl_score,
                    'final_score' => $final,
                ];
            });

        return response()->json(['students' => $students]);
    }

    public function upsert(Request $request): JsonResponse
    {
        $data = $request->validate([
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
            'theory_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'practice_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'ukk_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'pkl_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'task_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'uts_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'uas_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        if (! isset($data['practice_score']) && isset($data['task_score'])) {
            $data['practice_score'] = $data['task_score'];
        }
        if (! isset($data['theory_score']) && isset($data['uts_score'])) {
            $data['theory_score'] = $data['uts_score'];
        }
        if (! isset($data['ukk_score']) && isset($data['uas_score'])) {
            $data['ukk_score'] = $data['uas_score'];
        }

        $grade = $this->gradeService->upsert($request->user(), $data);
        $this->logger->log($request->user()->id, 'upserted', 'student_grades', $grade->id);

        return response()->json(['message' => 'Nilai berhasil disimpan.', 'grade' => $grade->load('student', 'subject')]);
    }

    /**
     * Batch upsert grades for multiple students in a class
     */
    public function batchUpsert(Request $request): JsonResponse
    {
        $data = $request->validate([
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
            'grades' => ['required', 'array'],
            'grades.*.student_id' => ['required', 'integer', 'exists:students,id'],
            'grades.*.theory_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'grades.*.practice_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'grades.*.ukk_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'grades.*.pkl_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $savedCount = 0;
        foreach ($data['grades'] as $item) {
            $item['subject_id'] = $data['subject_id'];
            $this->gradeService->upsert($request->user(), $item);
            $savedCount++;
        }

        $this->logger->log($request->user()->id, 'batch_upserted', 'student_grades', $savedCount);

        return response()->json([
            'message' => "Nilai untuk {$savedCount} siswa berhasil disimpan.",
            'count' => $savedCount,
        ]);
    }

    public function show(StudentGrade $grade): JsonResponse
    {
        return response()->json($grade->load('student', 'subject', 'teacher'));
    }
}
