<?php

namespace App\Http\Controllers\Api\Hubin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterDataController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $students = DB::table('students')->select('id', 'name')->orderBy('name')->get();
        $dudis = DB::table('dudi_partners')->select('id', 'company_name')->orderBy('company_name')->get();

        return response()->json([
            'students' => $students,
            'dudis' => $dudis,
        ]);
    }
}
