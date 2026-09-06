<?php

namespace App\Http\Controllers\Api\Eskul;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterDataController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $users = DB::table('users')->select('id', 'name')->orderBy('name')->get();
        $extracurriculars = DB::table('extracurriculars')->select('id', 'name')->orderBy('name')->get();

        return response()->json([
            'users' => $users,
            'extracurriculars' => $extracurriculars,
        ]);
    }
}
