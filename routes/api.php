<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ScheduleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// =================================================================
// GROUP MIDDLEWARE: API KEY PROTECTION (Keamanan Lapis 1)
// Semua route di dalam sini WAJIB mengirimkan header 'X-API-KEY'
// =================================================================
Route::middleware(['api.key'])->group(function () {

    // -------------------------------------------------------------
    // PUBLIC ROUTES 
    // (Bisa diakses tanpa login/Bearer Token, tapi tetap butuh API Key)
    // -------------------------------------------------------------
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    
    // Fitur Pencarian Jadwal (User bisa lihat jadwal sebelum login)
    Route::get('/schedules', [ScheduleController::class, 'index']);


    // -------------------------------------------------------------
    // PROTECTED ROUTES 
    // (Keamanan Lapis 2: WAJIB Login / butuh Bearer Token Sanctum)
    // -------------------------------------------------------------
    Route::middleware('auth:sanctum')->group(function () {
        
        // --- Profil & Auth ---
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        Route::post('/logout', [AuthController::class, 'logout']);
        
        // --- Fitur Utama Booking ---
        // 1. Order Tiket Baru
        Route::post('/order-ticket', [BookingController::class, 'orderTicket']);
        
        // 2. Lihat Riwayat Tiket Saya
        Route::get('/my-bookings', [BookingController::class, 'myBookings']);
        
        // 3. Simulasi Pembayaran Tiket (Ubah status pending -> paid)
        Route::post('/bookings/{id}/pay', [BookingController::class, 'payTicket']);
        
        // 4. Pembatalan Tiket (Ubah status -> cancelled)
        Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancelTicket']);
    });

});