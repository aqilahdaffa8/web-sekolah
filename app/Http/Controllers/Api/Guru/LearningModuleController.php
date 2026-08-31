<?php

namespace App\Http\Controllers\Api\Guru;

use App\Http\Controllers\Controller;
use App\Models\LearningModule;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LearningModuleController extends Controller
{
    public function __construct(private ActivityLogService $logger) {}

    public function index(Request $request): JsonResponse
    {
        $teacher = $request->user();

        return response()->json(
            LearningModule::with(['subject', 'classRoom'])
                ->where('teacher_id', $teacher->id)
                ->when($request->subject_id, fn ($q) => $q->where('subject_id', $request->subject_id))
                ->latest()
                ->paginate(15)
        );
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file_path' => ['nullable', 'string'],
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
        ]);

        $data['teacher_id'] = $request->user()->id;

        $module = LearningModule::create($data);
        $this->logger->log($request->user()->id, 'created', 'learning_modules', $module->id);

        return response()->json(['message' => 'Module created.', 'module' => $module->load('subject', 'classRoom')], 201);
    }

    public function show(LearningModule $learningModule): JsonResponse
    {
        return response()->json($learningModule->load('subject', 'classRoom', 'teacher'));
    }

    public function update(Request $request, LearningModule $learningModule): JsonResponse
    {
        $this->authorize('update', $learningModule, $request->user());

        $data = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file_path' => ['nullable', 'string'],
        ]);

        $learningModule->update($data);
        $this->logger->log($request->user()->id, 'updated', 'learning_modules', $learningModule->id);

        return response()->json(['message' => 'Module updated.', 'module' => $learningModule]);
    }

    public function destroy(Request $request, LearningModule $learningModule): JsonResponse
    {
        $this->logger->log($request->user()->id, 'deleted', 'learning_modules', $learningModule->id);
        $learningModule->delete();

        return response()->json(['message' => 'Module deleted.']);
    }

    /**
     * Manually authorize ownership since policies aren't registered.
     */
    private function authorize(string $action, LearningModule $module, $user): void
    {
        if ($module->teacher_id !== $user->id && ! $user->hasRole('Super Admin')) {
            abort(403, 'You can only modify your own modules.');
        }
    }
}
