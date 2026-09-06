<?php

use Illuminate\Support\Facades\Route;

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
    });
});
