<?php

use App\Http\Controllers\Api\Admin\ActivityLogController;
use App\Http\Controllers\Api\Admin\BackupController;
use App\Http\Controllers\Api\Admin\BannerController;
use App\Http\Controllers\Api\Admin\MenuController;
use App\Http\Controllers\Api\Admin\PostController;
use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Admin\SiteSettingController;
use App\Http\Controllers\Api\Admin\StudentController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\StudentActivationController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\Eskul\AchievementController;
use App\Http\Controllers\Api\Eskul\ExtracurricularController;
use App\Http\Controllers\Api\Eskul\RegistrationController;
use App\Http\Controllers\Api\Guru\FacilityController;
use App\Http\Controllers\Api\Guru\GradeController;
use App\Http\Controllers\Api\Guru\LearningModuleController;
use App\Http\Controllers\Api\Hubin\DudiPartnerController;
use App\Http\Controllers\Api\Hubin\JobVacancyController;
use App\Http\Controllers\Api\Hubin\MasterDataController;
use App\Http\Controllers\Api\Hubin\TracerStudyController;
use App\Http\Controllers\Api\Koperasi\TefaOrderController;
use App\Http\Controllers\Api\Koperasi\TefaProductController;
use App\Http\Controllers\Api\Public\ContactController;
use App\Http\Controllers\Api\Public\EskulPublicController;
use App\Http\Controllers\Api\Public\HomeController;
use App\Http\Controllers\Api\Public\HubinPublicController;
use App\Http\Controllers\Api\Public\NewsController;
use App\Http\Controllers\Api\Public\ProfileController;
use App\Http\Controllers\Api\Public\TefaPublicController;
use App\Http\Controllers\Api\Student\ExtracurricularRegistrationController as StudentExtracurricularRegistrationController;
use App\Http\Controllers\Api\Student\StudentGradeController;
use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────────────────────────────────────
// PUBLIC ENDPOINTS (no authentication required)
// ─────────────────────────────────────────────────────────────────────────────
Route::prefix('public')->group(function () {
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::get('/hubin', [HubinPublicController::class, 'index']);
    Route::get('/tefa', [TefaPublicController::class, 'index']);
    Route::get('/extracurriculars', [EskulPublicController::class, 'index']);
    Route::get('/achievements', [EskulPublicController::class, 'achievements']);
    Route::get('/news', [NewsController::class, 'index']);
    Route::get('/news/{post}', [NewsController::class, 'show']);
    Route::get('/events', [NewsController::class, 'events']);
    Route::get('/agenda', [NewsController::class, 'agenda']);
    Route::post('/orders', [TefaPublicController::class, 'placeOrder']);
    Route::post('/contact', [ContactController::class, 'store']);
});

// ─────────────────────────────────────────────────────────────────────────────
// AUTH ENDPOINTS
// ─────────────────────────────────────────────────────────────────────────────
Route::prefix('auth')->group(function () {
    // Rate-limited login: 5 attempts per minute per IP
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

    // Student account self-activation
    Route::post('/student/check-nis', [StudentActivationController::class, 'checkNis'])->middleware('throttle:10,1');
    Route::post('/student/activate', [StudentActivationController::class, 'activate'])->middleware('throttle:5,1');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});

// ─────────────────────────────────────────────────────────────────────────────
// PROTECTED ENDPOINTS
// ─────────────────────────────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

    Route::post('/extracurricular-registrations', [StudentExtracurricularRegistrationController::class, 'store'])
        ->middleware('role:Siswa,Super Admin');

    Route::prefix('student')->middleware('role:Siswa')->group(function () {
        Route::post('/grades', [StudentGradeController::class, 'index']);
        Route::post('/grades/download', [StudentGradeController::class, 'download']);
    });

    // ── SUPER ADMIN ──────────────────────────────────────────────────────────
    Route::prefix('admin')->middleware('role:Super Admin')->group(function () {

        Route::get('/students/classes', [StudentController::class, 'classes']);
        Route::get('/students/template', [StudentController::class, 'template']);
        Route::post('/students/import', [StudentController::class, 'import']);
        Route::apiResource('/students', StudentController::class)->only(['index', 'store', 'update', 'destroy']);

        // Users
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::put('/users/{user}', [UserController::class, 'update']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);
        Route::post('/users/{user}/roles', [UserController::class, 'assignRole']);
        Route::delete('/users/{user}/roles/{role}', [UserController::class, 'revokeRole']);

        // Roles & Permissions
        Route::get('/roles', [RoleController::class, 'index']);
        Route::post('/roles', [RoleController::class, 'store']);
        Route::get('/roles/{role}', [RoleController::class, 'show']);
        Route::put('/roles/{role}', [RoleController::class, 'update']);
        Route::delete('/roles/{role}', [RoleController::class, 'destroy']);
        Route::post('/roles/{role}/permissions', [RoleController::class, 'assignPermission']);
        Route::delete('/roles/{role}/permissions/{permission}', [RoleController::class, 'revokePermission']);
        Route::get('/permissions', [RoleController::class, 'permissions']);
        Route::post('/permissions', [RoleController::class, 'storePermission']);

        // Content management
        Route::apiResource('/banners', BannerController::class);
        Route::apiResource('/menus', MenuController::class)->except('show');
        Route::get('/site-settings', [SiteSettingController::class, 'index']);
        Route::post('/site-settings', [SiteSettingController::class, 'upsert']);
        Route::apiResource('/posts', PostController::class);

        // Activity log
        Route::get('/activity-logs', [ActivityLogController::class, 'index']);

        // Database backup
        Route::get('/backup', [BackupController::class, 'index']);
        Route::post('/backup', [BackupController::class, 'store']);
        Route::get('/backup/{filename}', [BackupController::class, 'download']);
    });

    // ── HUBIN & BKK ──────────────────────────────────────────────────────────
    Route::prefix('hubin')->middleware('role:Hubin,Super Admin')->group(function () {
        Route::get('/master-data', [MasterDataController::class, 'index']);
        Route::apiResource('/dudi-partners', DudiPartnerController::class);
        Route::apiResource('/job-vacancies', JobVacancyController::class);
        Route::apiResource('/tracer-studies', TracerStudyController::class);
    });

    // ── KOPERASI & TEFA ──────────────────────────────────────────────────────
    Route::prefix('koperasi')->middleware('role:Koperasi,Super Admin')->group(function () {
        Route::get('/master-data', [App\Http\Controllers\Api\Koperasi\MasterDataController::class, 'index']);
        Route::delete('/products/{product}', [TefaProductController::class, 'destroy']);
        Route::apiResource('/products', TefaProductController::class)->parameters(['products' => 'product']);
        Route::get('/orders', [TefaOrderController::class, 'index']);
        Route::get('/orders/{tefaOrder}', [TefaOrderController::class, 'show']);
        Route::patch('/orders/{tefaOrder}/status', [TefaOrderController::class, 'updateStatus']);
    });

    // ── GURU & AKADEMIK ───────────────────────────────────────────────────────
    Route::prefix('guru')->middleware('role:Guru,Super Admin')->group(function () {
        Route::get('/master-data', [App\Http\Controllers\Api\Guru\MasterDataController::class, 'index']);
        Route::apiResource('/learning-modules', LearningModuleController::class);
        Route::get('/grades/students-by-class', [GradeController::class, 'studentsByClass']);
        Route::post('/grades/batch', [GradeController::class, 'batchUpsert']);
        Route::get('/grades', [GradeController::class, 'index']);
        Route::post('/grades', [GradeController::class, 'upsert']);
        Route::get('/grades/{grade}', [GradeController::class, 'show']);
        Route::apiResource('/facilities', FacilityController::class);
    });

    // ── ESKUL ─────────────────────────────────────────────────────────────────
    Route::prefix('eskul')->middleware('role:Eskul,Super Admin')->group(function () {
        Route::get('/master-data', [App\Http\Controllers\Api\Eskul\MasterDataController::class, 'index']);
        Route::apiResource('/extracurriculars', ExtracurricularController::class);
        Route::get('/registrations', [RegistrationController::class, 'index']);
        Route::patch('/registrations/{registration}/status', [RegistrationController::class, 'updateStatus']);
        Route::apiResource('/achievements', AchievementController::class);
    });
});
