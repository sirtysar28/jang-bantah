<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Website Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang', [HomeController::class, 'about'])->name('about');
Route::get('/peta-publik', [HomeController::class, 'mapPublic'])->name('map.public');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/captcha/refresh', [AuthController::class, 'refreshCaptcha'])->name('captcha.refresh');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Dashboard Routes (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:super_admin,admin_aset,opd,pimpinan'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Master Data
    Route::resource('kecamatan', App\Http\Controllers\Admin\KecamatanController::class)->middleware('role:super_admin,admin_aset');
    Route::resource('desa', App\Http\Controllers\Admin\DesaController::class)->middleware('role:super_admin,admin_aset');
    Route::resource('opd', App\Http\Controllers\Admin\OpdController::class)->middleware('role:super_admin,admin_aset');

    // User Management
    Route::resource('user', App\Http\Controllers\Admin\UserManagementController::class)->middleware('role:super_admin');
    Route::get('audit-log', [App\Http\Controllers\Admin\UserManagementController::class, 'auditLog'])->name('audit-log')->middleware('role:super_admin');

    // Aset (Data Tanah)
    Route::resource('aset', App\Http\Controllers\Admin\AsetController::class)->except(['destroy']);
    Route::delete('aset/{aset}', [App\Http\Controllers\Admin\AsetController::class, 'destroy'])->name('aset.destroy')->middleware('role:super_admin');
    Route::post('aset/{aset}/validate', [App\Http\Controllers\Admin\AsetController::class, 'validateAset'])->name('aset.validate')->middleware('role:super_admin,admin_aset');

    // Dokumen
    Route::post('aset/{aset}/dokumen', [App\Http\Controllers\Admin\DokumenController::class, 'store'])->name('dokumen.store');
    Route::get('dokumen/{dokumen}/download', [App\Http\Controllers\Admin\DokumenController::class, 'download'])->name('dokumen.download');
    Route::delete('dokumen/{dokumen}', [App\Http\Controllers\Admin\DokumenController::class, 'destroy'])->name('dokumen.destroy');

    // Pengamanan Administrasi
    Route::get('pengamanan/administrasi', [App\Http\Controllers\Admin\PengamananAdministrasiController::class, 'index'])->name('pengamanan-admin.index');
    Route::post('aset/{aset}/pengamanan-administrasi', [App\Http\Controllers\Admin\PengamananAdministrasiController::class, 'updateOrCreate'])->name('pengamanan-admin.store');

    // Pengamanan Fisik
    Route::get('pengamanan/fisik', [App\Http\Controllers\Admin\PengamananFisikController::class, 'index'])->name('pengamanan-fisik.index');
    Route::post('aset/{aset}/pengamanan-fisik', [App\Http\Controllers\Admin\PengamananFisikController::class, 'updateOrCreate'])->name('pengamanan-fisik.store');

    // Pengamanan Hukum
    Route::get('pengamanan/hukum', [App\Http\Controllers\Admin\PengamananHukumController::class, 'index'])->name('pengamanan-hukum.index');
    Route::post('aset/{aset}/pengamanan-hukum', [App\Http\Controllers\Admin\PengamananHukumController::class, 'updateOrCreate'])->name('pengamanan-hukum.store');

    // Sertifikasi
    Route::get('sertifikasi', [App\Http\Controllers\Admin\SertifikasiController::class, 'index'])->name('sertifikasi.index');
    Route::put('sertifikasi/{sertifikasi}', [App\Http\Controllers\Admin\SertifikasiController::class, 'update'])->name('sertifikasi.update');
    Route::get('aset/{aset}/sertifikasi/create', [App\Http\Controllers\Admin\SertifikasiController::class, 'create'])->name('sertifikasi.create');
    Route::post('aset/{aset}/sertifikasi', [App\Http\Controllers\Admin\SertifikasiController::class, 'store'])->name('sertifikasi.store');

    // GIS (Peta Digital)
    Route::get('gis', [App\Http\Controllers\Admin\GISController::class, 'index'])->name('gis.index');
    Route::get('gis/data', [App\Http\Controllers\Admin\GISController::class, 'getGeoJson'])->name('gis.data');

    // Monitoring
    Route::get('monitoring', [App\Http\Controllers\Admin\MonitoringController::class, 'index'])->name('monitoring.index');
    Route::post('aset/{aset}/monitoring', [App\Http\Controllers\Admin\MonitoringController::class, 'store'])->name('monitoring.store');
    Route::post('monitoring/{monitoring}', [App\Http\Controllers\Admin\MonitoringController::class, 'update'])->name('monitoring.update');

    // Pemanfaatan Aset
    Route::get('pemanfaatan', [App\Http\Controllers\Admin\PemanfaatanController::class, 'index'])->name('pemanfaatan.index');
    Route::post('aset/{aset}/pemanfaatan', [App\Http\Controllers\Admin\PemanfaatanController::class, 'store'])->name('pemanfaatan.store');
    Route::post('pemanfaatan/{pemanfaatan}', [App\Http\Controllers\Admin\PemanfaatanController::class, 'update'])->name('pemanfaatan.update');

    // Laporan
    Route::get('laporan', [App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/pdf', [App\Http\Controllers\Admin\LaporanController::class, 'exportPdf'])->name('laporan.pdf');
    Route::get('laporan/excel', [App\Http\Controllers\Admin\LaporanController::class, 'exportExcel'])->name('laporan.excel');

    // Notifikasi
    Route::get('notifikasi', [App\Http\Controllers\Admin\NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('notifikasi/{notifikasi}/read', [App\Http\Controllers\Admin\NotifikasiController::class, 'markAsRead'])->name('notifikasi.read');
    Route::post('notifikasi/read-all', [App\Http\Controllers\Admin\NotifikasiController::class, 'markAllRead'])->name('notifikasi.read-all');

    // Settings
    Route::get('setting', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('setting.index')->middleware('role:super_admin');
    Route::post('setting/smtp', [App\Http\Controllers\Admin\SettingController::class, 'updateSmtp'])->name('setting.smtp')->middleware('role:super_admin');
    Route::post('setting/test-mail', [App\Http\Controllers\Admin\SettingController::class, 'testMail'])->name('setting.test-mail')->middleware('role:super_admin');
    Route::post('setting/backup', [App\Http\Controllers\Admin\SettingController::class, 'backupDatabase'])->name('setting.backup')->middleware('role:super_admin');
});
