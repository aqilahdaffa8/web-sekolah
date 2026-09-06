<?php

use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────────────────────
// PUBLIC PAGES
// ─────────────────────────────────────────────────────────────
Route::get('/', fn () => view('public.home'))->name('home');
Route::get('/home', fn () => redirect()->route('home'));

Route::get('/profil', fn () => view('public.profile'))->name('profile');
Route::get('/profile', fn () => redirect()->route('profile'));

Route::get('/hubin', fn () => view('public.hubin'))->name('hubin');

Route::get('/katalog', fn () => view('public.tefa'))->name('tefa');
Route::get('/tefa', fn () => redirect()->route('tefa'))->name('tefa.alias');
Route::get('/katalog-tefa', fn () => redirect()->route('tefa'));

Route::get('/ekstrakurikuler', fn () => view('public.eskul'))->name('eskul');
Route::get('/eskul', fn () => redirect()->route('eskul'))->name('eskul.alias');

Route::get('/berita', fn () => view('public.news'))->name('news');
Route::get('/news', fn () => redirect()->route('news'));
Route::get('/berita/{slug}', fn ($slug) => view('public.news-detail', ['slug' => $slug]))->name('news.detail');
Route::get('/news/{slug}', fn ($slug) => redirect()->route('news.detail', ['slug' => $slug]));

Route::get('/kontak', fn () => view('public.contact'))->name('contact');
Route::get('/contact', fn () => redirect()->route('contact'));

// Allow browser module imports from /resources/js/*.js
Route::get('/resources/js/{file}', function ($file) {
    $path = resource_path('js/' . $file);
    if (file_exists($path)) {
        return response()->file($path, ['Content-Type' => 'application/javascript']);
    }
    abort(404);
})->where('file', '.*\.js$');

// ─────────────────────────────────────────────────────────────
// AUTH
// ─────────────────────────────────────────────────────────────
Route::get('/login',  fn () => view('auth.login'))->name('login');
Route::get('/logout', fn () => view('auth.login'))->name('logout'); // JS handles logout via API

// ─────────────────────────────────────────────────────────────
// DASHBOARD (all rendered by Blade, JS handles API + auth guard)
// ─────────────────────────────────────────────────────────────
Route::prefix('dashboard')->name('dashboard.')->group(function () {

    // Index — dashboard overview
    Route::get('/', fn () => view('dashboard.index'))->name('index');

    // Super Admin
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/',              fn () => view('dashboard.super-admin.users'));
        Route::get('/users',         fn () => view('dashboard.super-admin.users'))->name('users');
        Route::get('/roles',         fn () => view('dashboard.super-admin.roles'))->name('roles');
        Route::get('/konten',        fn () => view('dashboard.super-admin.posts'))->name('content');
        Route::get('/posts',         fn () => view('dashboard.super-admin.posts'))->name('posts');
        Route::get('/banners',       fn () => view('dashboard.super-admin.banners'))->name('banners');
        Route::get('/menus',         fn () => view('dashboard.super-admin.menus'))->name('menus');
        Route::get('/pengaturan',    fn () => view('dashboard.super-admin.banners'))->name('settings');
        Route::get('/log',           fn () => view('dashboard.super-admin.activity-logs'))->name('logs');
        Route::get('/activity-logs', fn () => view('dashboard.super-admin.activity-logs'))->name('activity-logs');
        Route::get('/backup',        fn () => view('dashboard.super-admin.activity-logs'))->name('backup');
    });

    // Super Admin aliases (for /dashboard/super-admin/...)
    Route::prefix('super-admin')->group(function () {
        Route::get('/',              fn () => view('dashboard.super-admin.users'));
        Route::get('/users',         fn () => view('dashboard.super-admin.users'));
        Route::get('/roles',         fn () => view('dashboard.super-admin.roles'));
        Route::get('/banners',       fn () => view('dashboard.super-admin.banners'));
        Route::get('/menus',         fn () => view('dashboard.super-admin.menus'));
        Route::get('/posts',         fn () => view('dashboard.super-admin.posts'));
        Route::get('/konten',        fn () => view('dashboard.super-admin.posts'));
        Route::get('/log',           fn () => view('dashboard.super-admin.activity-logs'));
        Route::get('/activity-logs', fn () => view('dashboard.super-admin.activity-logs'));
    });

    // Hubin & BKK
    Route::prefix('hubin')->name('hubin.')->group(function () {
        Route::get('/',               fn () => view('dashboard.hubin.dudi'));
        Route::get('/mitra',          fn () => view('dashboard.hubin.dudi'))->name('dudi');
        Route::get('/dudi',           fn () => view('dashboard.hubin.dudi'));
        Route::get('/dudi-partners',  fn () => view('dashboard.hubin.dudi'));
        Route::get('/loker',          fn () => view('dashboard.hubin.jobs'))->name('jobs');
        Route::get('/jobs',           fn () => view('dashboard.hubin.jobs'));
        Route::get('/job-vacancies',  fn () => view('dashboard.hubin.jobs'));
        Route::get('/tracer',         fn () => view('dashboard.hubin.tracer'))->name('tracer');
        Route::get('/tracer-studies', fn () => view('dashboard.hubin.tracer'));
        Route::get('/tracer-study',   fn () => view('dashboard.hubin.tracer'));
    });

    // Koperasi & TeFA
    Route::prefix('koperasi')->name('koperasi.')->group(function () {
        Route::get('/',         fn () => view('dashboard.koperasi.products'));
        Route::get('/produk',   fn () => view('dashboard.koperasi.products'))->name('products');
        Route::get('/products', fn () => view('dashboard.koperasi.products'));
        Route::get('/pesanan',  fn () => view('dashboard.koperasi.orders'))->name('orders');
        Route::get('/orders',   fn () => view('dashboard.koperasi.orders'));
    });

    // Guru & Akademik
    Route::prefix('guru')->name('guru.')->group(function () {
        Route::get('/',                 fn () => view('dashboard.guru.grades'));
        Route::get('/nilai',            fn () => view('dashboard.guru.grades'))->name('grades');
        Route::get('/grades',           fn () => view('dashboard.guru.grades'));
        Route::get('/modul',            fn () => view('dashboard.guru.modules'))->name('modules');
        Route::get('/modules',          fn () => view('dashboard.guru.modules'));
        Route::get('/learning-modules', fn () => view('dashboard.guru.modules'));
        Route::get('/fasilitas',        fn () => view('dashboard.guru.facilities'))->name('facilities');
        Route::get('/facilities',       fn () => view('dashboard.guru.facilities'));
    });

    // Eskul
    Route::prefix('eskul')->name('eskul.')->group(function () {
        Route::get('/',                   fn () => view('dashboard.eskul.extracurriculars'));
        Route::get('/kelola',             fn () => view('dashboard.eskul.extracurriculars'))->name('list');
        Route::get('/extracurriculars',   fn () => view('dashboard.eskul.extracurriculars'));
        Route::get('/pendaftaran',        fn () => view('dashboard.eskul.registrations'))->name('registrations');
        Route::get('/registrations',      fn () => view('dashboard.eskul.registrations'));
        Route::get('/prestasi',           fn () => view('dashboard.eskul.achievements'))->name('achievements');
        Route::get('/achievements',       fn () => view('dashboard.eskul.achievements'));
    });
});
