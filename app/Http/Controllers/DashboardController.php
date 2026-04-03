<?php

namespace App\Http\Controllers;

use App\Models\DailySale;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        // Registro de hoy
        $todayRecord = DailySale::whereDate('date', $today)->first();

        // Registro de hace un año exacto
        $lastYearDate = $today->copy()->subYear();
        $lastYearRecord = DailySale::whereDate('date', $lastYearDate)->first();

        return Inertia::render('Dashboard', [
            'todayCash'     => $todayRecord?->cash ?? 0,
            'todayCard'     => $todayRecord?->card ?? 0,
            'todayTotal'    => $todayRecord?->total_sales ?? 0,
            'lastYearTotal' => $lastYearRecord?->total_sales ?? 0,
            'lastYearDate'  => $lastYearDate->format('Y-m-d'),
        ]);
    }
}
