<?php

use Illuminate\Support\Facades\Route;

<<<<<<< HEAD
// ─────────────────────────────────────────────────────────────
// PUBLIC PAGES
// ─────────────────────────────────────────────────────────────
Route::get('/', fn () => view('public.home'))->name('home');
Route::get('/profil', fn () => view('public.profile'))->name('profile');
Route::get('/hubin', fn () => view('public.hubin'))->name('hubin');
Route::get('/katalog', fn () => view('public.tefa'))->name('tefa');
Route::get('/ekstrakurikuler', fn () => view('public.eskul'))->name('eskul');
Route::get('/berita', fn () => view('public.news'))->name('news');
Route::get('/berita/{slug}', fn ($slug) => view('public.news-detail', ['slug' => $slug]))->name('news.detail');
Route::get('/kontak', fn () => view('public.contact'))->name('contact');

// ─────────────────────────────────────────────────────────────
// AUTH
// ─────────────────────────────────────────────────────────────
Route::get('/login',  fn () => view('auth.login'))->name('login');
Route::get('/logout', fn () => view('auth.login'))->name('logout'); // JS handles logout via API

// ─────────────────────────────────────────────────────────────
// DASHBOARD (all rendered by Blade, JS handles API + auth guard)
// ─────────────────────────────────────────────────────────────
Route::prefix('dashboard')->name('dashboard.')->group(function () {

    // Index — redirect logic handled by JS (based on stored role)
    Route::get('/', fn () => view('dashboard.index'))->name('index');

    // Super Admin
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/users',    fn () => view('dashboard.super-admin.users'))->name('users');
        Route::get('/roles',    fn () => view('dashboard.super-admin.roles'))->name('roles');
        Route::get('/konten',   fn () => view('dashboard.super-admin.content'))->name('content');
        Route::get('/pengaturan', fn () => view('dashboard.super-admin.settings'))->name('settings');
        Route::get('/log',      fn () => view('dashboard.super-admin.logs'))->name('logs');
        Route::get('/backup',   fn () => view('dashboard.super-admin.backup'))->name('backup');
    });

    // Hubin & BKK
    Route::prefix('hubin')->name('hubin.')->group(function () {
        Route::get('/mitra',    fn () => view('dashboard.hubin.dudi'))->name('dudi');
        Route::get('/loker',    fn () => view('dashboard.hubin.jobs'))->name('jobs');
        Route::get('/tracer',   fn () => view('dashboard.hubin.tracer'))->name('tracer');
    });

    // Koperasi & TeFA
    Route::prefix('koperasi')->name('koperasi.')->group(function () {
        Route::get('/produk',   fn () => view('dashboard.koperasi.products'))->name('products');
        Route::get('/pesanan',  fn () => view('dashboard.koperasi.orders'))->name('orders');
    });

    // Guru & Akademik
    Route::prefix('guru')->name('guru.')->group(function () {
        Route::get('/nilai',    fn () => view('dashboard.guru.grades'))->name('grades');
        Route::get('/modul',    fn () => view('dashboard.guru.modules'))->name('modules');
        Route::get('/fasilitas', fn () => view('dashboard.guru.facilities'))->name('facilities');
    });

    // Eskul
    Route::prefix('eskul')->name('eskul.')->group(function () {
        Route::get('/kelola',      fn () => view('dashboard.eskul.extracurriculars'))->name('list');
        Route::get('/pendaftaran', fn () => view('dashboard.eskul.registrations'))->name('registrations');
        Route::get('/prestasi',    fn () => view('dashboard.eskul.achievements'))->name('achievements');
=======
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
>>>>>>> dbce877d0f289c11f00a4851f99f3a28482b2d43
    });
});
