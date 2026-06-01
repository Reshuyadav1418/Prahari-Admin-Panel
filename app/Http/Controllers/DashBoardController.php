<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prahari;
use App\Models\Cases;
use App\Models\Challan;
use App\Models\Payment;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function userDashboard(){
        return view('layouts.userDashboard');
    }

    public function adminDashboard(){
        // Fetch dynamic data
        $totalPrahari = Prahari::count();
        $totalCases = Cases::count();
        $totalChallans = Challan::count();
        $totalRevenue = Transaction::where('status', 'success')->sum('amount_paid');
        $pendingWithdrawals = Transaction::where('status', 'pending')->sum('amount_paid');
        $todaysCases = Cases::whereDate('created_at', Carbon::today())->count();
        $todaysChallans = Challan::whereDate('created_at', Carbon::today())->count();
        $activePrahari = Prahari::where('status', 1)->count();

        // Chart Data: Last 6 months Cases
        $casesChartLabels = [];
        $casesChartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::today()->startOfMonth()->subMonths($i);
            $casesChartLabels[] = $date->format('M');
            $casesChartData[] = Cases::whereYear('created_at', $date->year)
                                     ->whereMonth('created_at', $date->month)
                                     ->count();
        }

        // Chart Data: Challan Status
        $challanPaid = Challan::where('status', 'paid')->count();
        $challanPending = Challan::where('status', 'pending')->count();
        $challanCancelled = Challan::where('status', 'cancelled')->count();

        return view('layouts.adminDashboard', compact(
            'totalPrahari', 'totalCases', 'totalChallans', 'totalRevenue', 
            'pendingWithdrawals', 'todaysCases', 'todaysChallans', 'activePrahari',
            'casesChartLabels', 'casesChartData', 'challanPaid', 'challanPending', 'challanCancelled'
        ));
    }

    public function getDashboardData(){
        $totalPrahari = Prahari::count();
        $totalCases = Cases::count();
        $totalChallans = Challan::count();
        $totalRevenue = Transaction::where('status', 'success')->sum('amount_paid');
        $pendingWithdrawals = Transaction::where('status', 'pending')->sum('amount_paid');
        $todaysCases = Cases::whereDate('created_at', Carbon::today())->count();
        $todaysChallans = Challan::whereDate('created_at', Carbon::today())->count();
        $activePrahari = Prahari::where('status', 1)->count();

        // Chart Data: Last 6 months Cases
        $casesChartLabels = [];
        $casesChartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::today()->startOfMonth()->subMonths($i);
            $casesChartLabels[] = $date->format('M');
            $casesChartData[] = Cases::whereYear('created_at', $date->year)
                                     ->whereMonth('created_at', $date->month)
                                     ->count();
        }

        // Chart Data: Challan Status
        $challanPaid = Challan::where('status', 'paid')->count();
        $challanPending = Challan::where('status', 'pending')->count();
        $challanCancelled = Challan::where('status', 'cancelled')->count();

        return response()->json([
            'totalPrahari' => $totalPrahari,
            'totalCases' => $totalCases,
            'totalChallans' => $totalChallans,
            'totalRevenue' => '₹ ' . number_format($totalRevenue, 2),
            'pendingWithdrawals' => '₹ ' . number_format($pendingWithdrawals, 2),
            'todaysCases' => $todaysCases,
            'todaysChallans' => $todaysChallans,
            'activePrahari' => $activePrahari,
            'chart' => [
                'casesChartLabels' => $casesChartLabels,
                'casesChartData' => $casesChartData,
                'challanPaid' => $challanPaid,
                'challanPending' => $challanPending,
                'challanCancelled' => $challanCancelled
            ]
        ]);
    }
}