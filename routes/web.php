<?php


use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;

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

Route::get('/', [AuthController::class, 'login']);

Route::get('/login', function () {
    return view('login');
});
Route::post('/login', [AuthController::class, 'actionLogin'])->name('login');
Route::get('/register-admin', function () {
    return view('register');
});
Route::post('/register-admin', [\App\Http\Controllers\Admin\PetugasController::class, 'register']);
Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [AuthController::class, 'actionLogout']);
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->middleware('auth');
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin']], function () {
    Route::get('/akun-petugas', [\App\Http\Controllers\Admin\PetugasController::class, 'index']);
    Route::post('/akun-petugas', [\App\Http\Controllers\Admin\PetugasController::class, 'store']);
    Route::post('/akun-petugas/import', [\App\Http\Controllers\Admin\PetugasController::class, 'import']);
    Route::put('/akun-petugas/{id}', [\App\Http\Controllers\Admin\PetugasController::class, 'update']);
    Route::delete('/akun-petugas/{id}', [\App\Http\Controllers\Admin\PetugasController::class, 'destroy']);

    Route::get('/jadwal', [\App\Http\Controllers\Admin\JadwalController::class, 'index']);
    Route::post('/jadwal', [\App\Http\Controllers\Admin\JadwalController::class, 'store']);
    Route::put('/jadwal/{id}', [\App\Http\Controllers\Admin\JadwalController::class, 'update']);
    Route::delete('/jadwal/{id}', [\App\Http\Controllers\Admin\JadwalController::class, 'destroy']);

    Route::get('/izin', [\App\Http\Controllers\Admin\IzinController::class, 'index']);
    Route::get('/izin/{id}/{code}', [\App\Http\Controllers\Admin\IzinController::class, 'setuju']);
    Route::get('/izin/export', [\App\Http\Controllers\Admin\IzinController::class, 'export']);

    Route::get('/genpres', [\App\Http\Controllers\DashboardController::class, 'genpres']);
    Route::get('/presensi', [\App\Http\Controllers\Admin\PresensiController::class, 'index']);
    Route::get('presensi/periode/{year}/{periode}', [\App\Http\Controllers\Admin\PresensiController::class, 'periode']);
    Route::get('presensi/periode/{year}/{periode}/export', [\App\Http\Controllers\Admin\PresensiController::class, 'export']);

    Route::get('/gaji', [\App\Http\Controllers\Admin\GajiController::class, 'index']);
    Route::get('/gaji/regenerate', [\App\Http\Controllers\Admin\GajiController::class, 'regenerate']);
    Route::get('/gaji/export', [\App\Http\Controllers\Admin\GajiController::class, 'report']);
    Route::get('/gaji/exportExcel', [\App\Http\Controllers\Admin\GajiController::class, 'exportExcel']);
    Route::get('/gaji/{id}', [\App\Http\Controllers\Admin\GajiController::class, 'detail']);
    Route::post('/gaji/{id}/export', [\App\Http\Controllers\Admin\GajiController::class, 'export']);

    Route::get('/pengaduan', [\App\Http\Controllers\Admin\PengaduanController::class, 'index']);
    Route::get('/pengaduan/export', [\App\Http\Controllers\Admin\PengaduanController::class, 'export']);
});
Route::group(['prefix' => 'petugas', 'middleware' => ['auth', 'role:user']], function () {
    Route::get('/jadwal', [\App\Http\Controllers\Admin\JadwalController::class, 'viewPetugas']);
    Route::get('/izin', [\App\Http\Controllers\Petugas\IzinController::class, 'index']);
    Route::post('/izin', [\App\Http\Controllers\Petugas\IzinController::class, 'store']);
    Route::get('/presensi', [\App\Http\Controllers\Petugas\PresensiController::class, 'index']);
    Route::post('/presensi', [\App\Http\Controllers\Petugas\PresensiController::class, 'absen']);
    Route::get('/presensi/riwayat/{id}', [\App\Http\Controllers\Petugas\PresensiController::class, 'riwayat']);
    Route::get('/presensi/riwayat/{id}/export', [\App\Http\Controllers\Petugas\PresensiController::class, 'export']);

    Route::get('/gaji', [\App\Http\Controllers\Admin\GajiController::class, 'viewPetugas']);
    Route::get('/gaji/{id}/export', [\App\Http\Controllers\Admin\GajiController::class, 'exportPetugas']);
    Route::get('/pengaduan', function () {
        return view('petugas.pengaduan');
    });
    Route::post('/pengaduan', [\App\Http\Controllers\Admin\PengaduanController::class, 'store']);
});

Route::get('/gitpullhooks', [\App\Http\Controllers\PullhookController::class, 'pull']);
