<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Traits\ApiResponse;

class ScheduleController extends Controller
{
    use ApiResponse;

    /**
     * FITUR 5: PENCARIAN JADWAL (Search & Filter)
     */
    public function index(Request $request)
    {
        // Mulai Query
        $query = Schedule::with(['bus', 'route']);

        if ($request->has('origin')) {
            $query->whereHas('route', function ($q) use ($request) {
                $q->where('origin', 'like', '%' . $request->origin . '%');
            });
        }

        if ($request->has('destination')) {
            $query->whereHas('route', function ($q) use ($request) {
                $q->where('destination', 'like', '%' . $request->destination . '%');
            });
        }

        $schedules = $query->orderBy('departure_time', 'asc')->get();

        return $this->successResponse($schedules, 'Daftar Jadwal Tersedia');
    }
}