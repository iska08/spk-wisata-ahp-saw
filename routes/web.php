<?php

use App\Http\Controllers\Admin\AhpController;
use App\Http\Controllers\Admin\AlternativeController;
use App\Http\Controllers\Admin\CriteriaController;
use App\Http\Controllers\Admin\SaranController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JenisController;
use App\Http\Controllers\Admin\KombinasiController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SawController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WisataController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\FreeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/',  [PortalController::class, 'index'])->name('portal.index');

Route::get('/spk', [FreeController::class, 'index'])->name('free.index');
Route::get('/spk/rekomendasi/{criteria_analysis}', [FreeController::class, 'rekomendasi'])->name('free.rekomendasi');

Route::get('/register', [RegisterController::class, 'index'])->name('register.index');
Route::post('/register', [RegisterController::class, 'register'])->name('register.register');
Route::get('/login',  [LoginController::class, 'index'])->middleware('guest')->name('login.index');
Route::post('/login',  [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::prefix('dashboard')
    // ->namespace('Admin')
    ->middleware('auth')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard.index');
        Route::resources([
            'data/kriteria'  => CriteriaController::class,
            'data/wisata'    => WisataController::class,
            'data/jenis'     => JenisController::class,
            'pengguna/users' => UserController::class,
            'sarans'         => SaranController::class,
        ], ['except' => 'show', 'middleware' => 'admin']);
        Route::put('/dashboard/sarans/{id}/approve', [SaranController::class, 'approve'])
            ->name('sarans.approve');
        // alternatif
        Route::resource('data/alternatif', AlternativeController::class)
            ->except('show');
        // profile
        Route::get('pengguna/profile', [ProfileController::class, 'index'])
            ->name('profile.index');
        Route::put('pengguna/profile/{users}', [ProfileController::class, 'update'])
            ->name('profile.update');
        // link slug
        Route::get('data/jenis/{jenis:slug}', [JenisController::class, 'wisatas'])
            ->name('jenis.wisatas');
        // perhitungan
        Route::get('perhitungan/setting', [KombinasiController::class, 'index'])
            ->name('kombinasi.index');
        Route::get('perhitungan/metode', [KombinasiController::class, 'awal'])
            ->name('kombinasi.awal');
        Route::post('perhitungan/setting', [KombinasiController::class, 'store'])
            ->name('kombinasi.store');
        Route::get('perhitungan/setting/{criteria_analysis}', [KombinasiController::class, 'show'])
            ->name('kombinasi.show');
        Route::get('perhitungan/setting/perbandingan/{criteria_analysis}', [KombinasiController::class, 'show'])
            ->name('kombinasi.show');
        Route::put('perhitungan/setting/perbandingan/{criteria_analysis}', [KombinasiController::class, 'update'])
            ->name('kombinasi.update');
        Route::get('perhitungan/setting/bobot/{criteria_analysis}', [KombinasiController::class, 'showBobot'])
            ->name('kombinasi.showBobot');
        Route::put('perhitungan/setting/bobot/{criteria_analysis}', [KombinasiController::class, 'updateBobot'])
            ->name('kombinasi.updateBobot');
        Route::delete('perhitungan/setting/{criteria_analysis}', [KombinasiController::class, 'destroy'])
            ->name('kombinasi.destroy');
        // kombinasi
        Route::get('perhitungan/metode/kombinasi/{criteria_analysis}', [KombinasiController::class, 'result'])
            ->name('kombinasi.result');
        Route::get('perhitungan/metode/kombinasi/detail/{criteria_analysis}', [KombinasiController::class, 'detail'])
            ->name('kombinasi.detail');
        // ahp
        Route::get('perhitungan/metode/ahp/{criteria_analysis}', [AhpController::class, 'result'])
            ->name('ahp.result');
        Route::get('perhitungan/metode/ahp/detail/{criteria_analysis}', [AhpController::class, 'detail'])
            ->name('ahp.detail');
        // saw
        Route::get('perhitungan/metode/saw/{criteria_analysis}', [SawController::class, 'result'])
            ->name('saw.result');
        Route::get('perhitungan/metode/saw/detail/{criteria_analysis}', [SawController::class, 'detail'])
            ->name('saw.detail');
    });