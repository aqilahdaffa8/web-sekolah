<?php

namespace App\Http\Controllers\Api\Guru;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use App\Services\GradeService;
use App\Models\StudentGrade;
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

        // Teachers only see grades they entered
        $grades = StudentGrade::with(['student.classRoom', 'subject'])
            ->where('teacher_id', $teacher->id)
            ->when($request->student_id, fn ($q) => $q->where('student_id', $request->student_id))
            ->when($request->subject_id, fn ($q) => $q->where('subject_id', $request->subject_id))
            ->paginate(20);

        return response()->json($grades);
    }

    public function upsert(Request $request): JsonResponse
    {
        $data = $request->validate([
            'student_id'     => ['required', 'integer', 'exists:students,id'],
            'subject_id'     => ['required', 'integer', 'exists:subjects,id'],
            'theory_score'   => ['nullable', 'numeric', 'min:0', 'max:100'],
            'practice_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'ukk_score'      => ['nullable', 'numeric', 'min:0', 'max:100'],
            'pkl_score'      => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $grade = $this->gradeService->upsert($request->user(), $data);
        $this->logger->log($request->user()->id, 'upserted', 'student_grades', $grade->id);

        return response()->json(['message' => 'Grade saved.', 'grade' => $grade->load('student', 'subject')]);
    }

    public function show(StudentGrade $grade): JsonResponse
    {
        return response()->json($grade->load('student', 'subject', 'teacher'));
    }
}
