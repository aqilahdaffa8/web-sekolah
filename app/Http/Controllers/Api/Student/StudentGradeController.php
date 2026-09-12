<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyStudentGradeRequest;
use App\Models\Student;
use App\Models\StudentGrade;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class StudentGradeController extends Controller
{
    public function index(VerifyStudentGradeRequest $request): JsonResponse
    {
        return response()->json($this->gradePayload($request));
    }

    public function download(VerifyStudentGradeRequest $request): Response
    {
        $payload = $this->gradePayload($request);
        $student = $payload['student'];
        $lines = [
            'LAPORAN NILAI SISWA',
            'SMKN 1 KATAPANG',
            '',
            'Nama : '.$student['name'],
            'NIS  : '.$student['nis'],
            'Kelas: '.$student['class_name'],
            '',
            'Mata Pelajaran | Teori | Praktik | UKK | PKL | Nilai Akhir',
            str_repeat('-', 78),
        ];

        foreach ($payload['grades'] as $grade) {
            $lines[] = sprintf(
                '%-20s | %5s | %7s | %3s | %3s | %11s',
                substr($grade['subject'], 0, 20),
                $grade['theory_score'] ?? '-',
                $grade['practice_score'] ?? '-',
                $grade['ukk_score'] ?? '-',
                $grade['pkl_score'] ?? '-',
                $grade['final_score'] ?? '-'
            );
        }

        if ($payload['grades'] === []) {
            $lines[] = 'Belum ada nilai yang diinput oleh guru.';
        }

        $lines[] = '';
        $lines[] = 'Dicetak pada: '.now()->format('d-m-Y H:i');

        return response($this->createPdf($lines), Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="nilai-'.$student['nis'].'.pdf"',
        ]);
    }

    /**
     * @return array{student: array{id: int, name: string, nis: string, class_name: string}, grades: array<int, array<string, float|int|string|null>>}
     */
    private function gradePayload(VerifyStudentGradeRequest $request): array
    {
        $student = Student::query()
            ->with('classRoom:id,class_name')
            ->where('user_id', $request->user()->id)
            ->where('nis', $request->string('nis')->toString())
            ->where('name', $request->string('name')->toString())
            ->first();

        if (! $student) {
            abort(Response::HTTP_NOT_FOUND, 'Data siswa tidak ditemukan atau tidak sesuai dengan akun Anda.');
        }

        $grades = StudentGrade::query()
            ->whereBelongsTo($student)
            ->with('subject:id,subject_name')
            ->orderBy('subject_id')
            ->get()
            ->map(function (StudentGrade $grade): array {
                $scores = array_filter([$grade->theory_score, $grade->practice_score, $grade->ukk_score, $grade->pkl_score], fn ($score) => $score !== null);

                return [
                    'subject' => $grade->subject?->subject_name ?? '-',
                    'theory_score' => $grade->theory_score,
                    'practice_score' => $grade->practice_score,
                    'ukk_score' => $grade->ukk_score,
                    'pkl_score' => $grade->pkl_score,
                    'final_score' => $scores === [] ? null : round(array_sum($scores) / count($scores), 1),
                ];
            })
            ->all();

        return [
            'student' => ['id' => $student->id, 'name' => $student->name, 'nis' => $student->nis, 'class_name' => $student->classRoom?->class_name ?? '-'],
            'grades' => $grades,
        ];
    }

    /**
     * Creates a lightweight, dependency-free PDF using the built-in Courier font.
     *
     * @param  array<int, string>  $lines
     */
    private function createPdf(array $lines): string
    {
        $pageLines = array_chunk($lines, 45);
        $objects = ['<< /Type /Catalog /Pages 2 0 R >>', ''];
        $pageObjectIds = [];

        foreach ($pageLines as $pageNumber => $page) {
            $pageObjectIds[] = count($objects) + 1;
            $content = "BT\n/F1 10 Tf\n50 790 Td\n";
            foreach ($page as $index => $line) {
                if ($index > 0) {
                    $content .= "0 -16 Td\n";
                }
                $content .= '('.str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $line).") Tj\n";
            }
            $contentObjectId = $pageObjectIds[$pageNumber] + 1;
            $objects[] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 '.($contentObjectId + 1)." 0 R >> >> /Contents {$contentObjectId} 0 R >>";
            $objects[] = '<< /Length '.strlen($content)." >>\nstream\n{$content}endstream";
            $objects[] = '<< /Type /Font /Subtype /Type1 /BaseFont /Courier >>';
        }

        $kids = implode(' ', array_map(fn (int $id): string => "{$id} 0 R", $pageObjectIds));
        $objects[1] = "<< /Type /Pages /Kids [{$kids}] /Count ".count($pageObjectIds).' >>';

        $pdf = "%PDF-1.4\n";
        $offsets = [0];
        foreach ($objects as $index => $object) {
            $offsets[] = strlen($pdf);
            $pdf .= ($index + 1)." 0 obj\n{$object}\nendobj\n";
        }
        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n0 ".(count($objects) + 1)."\n0000000000 65535 f \n";
        foreach (array_slice($offsets, 1) as $offset) {
            $pdf .= sprintf('%010d 00000 n ', $offset)."\n";
        }

        return $pdf."trailer\n<< /Size ".(count($objects) + 1)." /Root 1 0 R >>\nstartxref\n{$xrefOffset}\n%%EOF";
    }
}
