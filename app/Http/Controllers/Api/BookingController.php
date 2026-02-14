<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Schedule; 
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    use ApiResponse;

    /**
     * FITUR 1: ORDER DENGAN CEK KAPASITAS
     */
    public function orderTicket(Request $request)
    {
        $validated = $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
        ]);

        return DB::transaction(function () use ($request, $validated) {
            
            // 1. Ambil Data Jadwal beserta Bus-nya
            $schedule = Schedule::with('bus')->lockForUpdate()->find($validated['schedule_id']);

            // 2. Hitung jumlah kursi yang SUDAH terjual (status pending & paid)
            $bookedSeats = Booking::where('schedule_id', $schedule->id)
                ->whereIn('status', ['pending', 'paid'])
                ->count();

            // 3. Cek Kapasitas
            if ($bookedSeats >= $schedule->bus->capacity) {
                return $this->errorResponse('Maaf, kursi untuk jadwal ini sudah penuh.', 400);
            }

            // 4. Jika aman, buat booking
            $booking = $request->user()->bookings()->create([
                'schedule_id' => $validated['schedule_id'],
                'status' => 'pending' // Default menunggu pembayaran
            ]);

            return $this->successResponse($booking, 'Tiket berhasil dipesan. Silakan lakukan pembayaran.', 201);
        });
    }

    /**
     * FITUR 2: RIWAYAT PESANAN LENGKAP
     */
    public function myBookings(Request $request)
    {
        $bookings = $request->user()->bookings()
            ->with(['schedule.bus', 'schedule.route'])
            ->latest()
            ->get();
            
        return $this->successResponse($bookings, 'Riwayat pesanan Anda');
    }

    /**
     * FITUR 3: SIMULASI PEMBAYARAN
     */
    public function payTicket(Request $request, $id)
    {
        // Cari booking milik user yang sedang login
        $booking = $request->user()->bookings()->find($id);

        if (!$booking) {
            return $this->errorResponse('Booking tidak ditemukan.', 404);
        }

        if ($booking->status === 'paid') {
            return $this->errorResponse('Tiket ini sudah dibayar sebelumnya.', 400);
        }

        if ($booking->status === 'cancelled') {
            return $this->errorResponse('Tiket ini sudah dibatalkan.', 400);
        }

        // Update Status
        $booking->update(['status' => 'paid']);

        return $this->successResponse($booking, 'Pembayaran berhasil! Tiket Anda aktif.');
    }

    /**
     * FITUR 4: PEMBATALAN TIKET
     */
    public function cancelTicket(Request $request, $id)
    {
        $booking = $request->user()->bookings()->find($id);

        if (!$booking) {
            return $this->errorResponse('Booking tidak ditemukan.', 404);
        }

        if ($booking->status === 'paid') {
            return $this->errorResponse('Tiket yang sudah dibayar tidak bisa dibatalkan via aplikasi.', 400);
        }

        $booking->update(['status' => 'cancelled']);

        return $this->successResponse($booking, 'Pesanan berhasil dibatalkan.');
    }
}