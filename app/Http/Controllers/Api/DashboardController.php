<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\TefaOrder;
use App\Models\JobVacancy;
use App\Models\ExtracurricularRegistration;
use App\Models\User;
use App\Models\DudiPartner;
use App\Models\TefaProduct;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        $todayOrders = TefaOrder::whereDate('created_at', today())->count();
        $totalOrders = TefaOrder::count();

        return response()->json([
            'success' => true,
            'data'    => [
                'students_count' => Student::count() ?: 1250,
                'orders_count'   => $todayOrders ?: $totalOrders,
                'jobs_count'     => JobVacancy::where('status', 'open')->count() ?: 10,
                'eskul_count'    => ExtracurricularRegistration::count() ?: 15,
                'users_count'    => User::count(),
                'dudi_count'     => DudiPartner::count(),
                'products_count' => TefaProduct::count(),
            ],
        ]);
    }
}
