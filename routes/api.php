<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\AsetController;
use App\Http\Controllers\Api\SertifikasiController;
use App\Http\Controllers\Api\MonitoringController;
use App\Http\Controllers\Api\GISController;
use App\Http\Controllers\Api\LaporanController;
use App\Http\Controllers\Api\NotifikasiController;

/*
|--------------------------------------------------------------------------
| API Routes - JANG BANTAH (Flutter Ready)
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Aset
    Route::get('/aset', [AsetController::class, 'index']);
    Route::post('/aset', [AsetController::class, 'store']);
    Route::get('/aset/{id}', [AsetController::class, 'show']);
    Route::put('/aset/{id}', [AsetController::class, 'update']);
    Route::delete('/aset/{id}', [AsetController::class, 'destroy']);

    // Dokumen
    Route::get('/aset/{id}/dokumen', [AsetController::class, 'dokumen']);
    Route::post('/dokumen/upload', [AsetController::class, 'uploadDokumen']);

    // Sertifikasi
    Route::get('/sertifikasi', [SertifikasiController::class, 'index']);
    Route::put('/sertifikasi/{id}', [SertifikasiController::class, 'update']);

    // Monitoring
    Route::get('/monitoring', [MonitoringController::class, 'index']);

    // GIS
    Route::get('/map', [GISController::class, 'index']);

    // Laporan
    Route::get('/report/pdf', [LaporanController::class, 'pdf']);
    Route::get('/report/excel', [LaporanController::class, 'excel']);

    // Notifikasi
    Route::get('/notifikasi', [NotifikasiController::class, 'index']);
    Route::post('/notifikasi/read', [NotifikasiController::class, 'markRead']);
});
