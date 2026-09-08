<?php

namespace App\Http\Controllers\Api\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterDataController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $students = DB::table('students')->select('id', 'name', 'nis', 'class_id')->orderBy('name')->get();
        $subjects = DB::table('subjects')->select('id', 'subject_name as name', 'subject_name')->orderBy('subject_name')->get();
        $classes = DB::table('classes')->select('id', 'class_name as name', 'class_name')->orderBy('class_name')->get();
        $programs = DB::table('programs')->select('id', 'program_name as name', 'program_name')->orderBy('program_name')->get();

        return response()->json([
            'students' => $students,
            'subjects' => $subjects,
            'classes' => $classes,
            'programs' => $programs,
        ]);
    }
}
