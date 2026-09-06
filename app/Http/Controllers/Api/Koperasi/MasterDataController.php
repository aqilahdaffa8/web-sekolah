<?php

namespace App\Http\Controllers\Api\Koperasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterDataController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $programs = DB::table('programs')->select('id', 'name')->orderBy('name')->get();

        return response()->json([
            'programs' => $programs,
        ]);
    }
}
