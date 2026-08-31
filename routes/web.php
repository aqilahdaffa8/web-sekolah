<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Routes
Route::get('/', function () {
    return view('public.home');
});
Route::get('/profil', function () {
    return view('public.profile');
});
Route::get('/hubin', function () {
    return view('public.hubin');
});
Route::get('/tefa', function () {
    return view('public.tefa');
});
Route::get('/eskul', function () {
    return view('public.eskul');
});
Route::get('/berita', function () {
    return view('public.news.index');
});
Route::get('/berita/{id}', function ($id) {
    return view('public.news.show', ['id' => $id]);
});
Route::get('/kontak', function () {
    return view('public.contact');
});

// Auth Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Dashboard Shell (Router)
// Note: Actual routing based on role will be handled in frontend JS (SPA-like inside the dashboard)
// or we can map direct URLs for easier reloading.
Route::prefix('dashboard')->group(function () {
    Route::get('/', function () {
        return view('dashboard.index');
    });

    // Super Admin
    Route::prefix('super-admin')->group(function () {
        Route::get('/users', function () {
            return view('dashboard.super-admin.users');
        });
        Route::get('/roles', function () {
            return view('dashboard.super-admin.roles');
        });
        Route::get('/banners', function () {
            return view('dashboard.super-admin.banners');
        });
        Route::get('/menus', function () {
            return view('dashboard.super-admin.menus');
        });
        Route::get('/posts', function () {
            return view('dashboard.super-admin.posts');
        });
        Route::get('/activity-logs', function () {
            return view('dashboard.super-admin.activity-logs');
        });
    });

    // Hubin
    Route::prefix('hubin')->group(function () {
        Route::get('/dudi-partners', function () {
            return view('dashboard.hubin.dudi-partners');
        });
        Route::get('/job-vacancies', function () {
            return view('dashboard.hubin.job-vacancies');
        });
        Route::get('/tracer-studies', function () {
            return view('dashboard.hubin.tracer-study');
        });
    });

    // Koperasi & TeFA
    Route::prefix('koperasi')->group(function () {
        Route::get('/products', function () {
            return view('dashboard.koperasi.products');
        });
        Route::get('/orders', function () {
            return view('dashboard.koperasi.orders');
        });
    });

    // Guru
    Route::prefix('guru')->group(function () {
        Route::get('/grades', function () {
            return view('dashboard.guru.grades');
        });
        Route::get('/learning-modules', function () {
            return view('dashboard.guru.learning-modules');
        });
        Route::get('/facilities', function () {
            return view('dashboard.guru.facilities');
        });
    });

    // Eskul
    Route::prefix('eskul')->group(function () {
        Route::get('/extracurriculars', function () {
            return view('dashboard.eskul.extracurriculars');
        });
        Route::get('/registrations', function () {
            return view('dashboard.eskul.registrations');
        });
        Route::get('/achievements', function () {
            return view('dashboard.eskul.achievements');
        });
    });
});
