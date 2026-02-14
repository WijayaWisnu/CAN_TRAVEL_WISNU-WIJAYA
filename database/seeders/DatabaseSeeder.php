<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bus;
use App\Models\Route;
use App\Models\Schedule;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ==========================================
        // 1. BUAT DATA BUS (Bervariasi tipe & kapasitas)
        // ==========================================
        $busExpress = Bus::create(['name' => 'PO CAN Express', 'plate_number' => 'B 1234 XYZ', 'capacity' => 40]);
        $busVip     = Bus::create(['name' => 'PO CAN VIP', 'plate_number' => 'B 5678 ABC', 'capacity' => 30]);
        $busSleeper = Bus::create(['name' => 'PO CAN Sleeper', 'plate_number' => 'D 9999 ZZ', 'capacity' => 20]);
        $busEkonomi = Bus::create(['name' => 'PO CAN Ekonomi', 'plate_number' => 'H 1111 XX', 'capacity' => 50]);

        // ==========================================
        // 2. BUAT DATA RUTE (Bervariasi kota & harga)
        // ==========================================
        $jktBdg = Route::create(['origin' => 'Jakarta', 'destination' => 'Bandung', 'price' => 150000]);
        $jktJog = Route::create(['origin' => 'Jakarta', 'destination' => 'Yogyakarta', 'price' => 350000]);
        $bdgSmg = Route::create(['origin' => 'Bandung', 'destination' => 'Semarang', 'price' => 250000]);
        $sbyJkt = Route::create(['origin' => 'Surabaya', 'destination' => 'Jakarta', 'price' => 450000]);
        $jogMlg = Route::create(['origin' => 'Yogyakarta', 'destination' => 'Malang', 'price' => 200000]);

        // ==========================================
        // 3. BUAT DATA JADWAL (Relasi Bus & Rute)
        // ==========================================
        
        // Jadwal 1: Jakarta - Bandung (Besok Pagi - VIP)
        Schedule::create([
            'bus_id' => $busVip->id,
            'route_id' => $jktBdg->id,
            'departure_time' => Carbon::tomorrow()->setTime(8, 0, 0)
        ]);

        // Jadwal 2: Jakarta - Bandung (Hari ini Malam - Ekonomi)
        Schedule::create([
            'bus_id' => $busEkonomi->id,
            'route_id' => $jktBdg->id,
            'departure_time' => Carbon::today()->setTime(20, 0, 0)
        ]);

        // Jadwal 3: Jakarta - Yogyakarta (Besok Malam - Sleeper)
        Schedule::create([
            'bus_id' => $busSleeper->id,
            'route_id' => $jktJog->id,
            'departure_time' => Carbon::tomorrow()->setTime(19, 30, 0)
        ]);

        // Jadwal 4: Surabaya - Jakarta (Lusa Sore - Express)
        Schedule::create([
            'bus_id' => $busExpress->id,
            'route_id' => $sbyJkt->id,
            'departure_time' => Carbon::now()->addDays(2)->setTime(16, 0, 0)
        ]);

        // Jadwal 5: Bandung - Semarang (Lusa Pagi - VIP)
        Schedule::create([
            'bus_id' => $busVip->id,
            'route_id' => $bdgSmg->id,
            'departure_time' => Carbon::now()->addDays(2)->setTime(7, 30, 0)
        ]);
        
        // Jadwal 6: Yogyakarta - Malang (Minggu depan - Express)
        Schedule::create([
            'bus_id' => $busExpress->id,
            'route_id' => $jogMlg->id,
            'departure_time' => Carbon::now()->addWeeks(1)->setTime(14, 0, 0)
        ]);
    }
}