<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ActivityLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        // Get statistics
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $adminUsers = User::where('is_admin', true)->count();
        
        // Get today's activities count
        $todayActivities = ActivityLog::whereDate('created_at', Carbon::today())->count();
        
        // Get recent activities (last 10)
        $recentActivities = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('dashboard', compact(
            'totalUsers',
            'activeUsers', 
            'adminUsers',
            'todayActivities',
            'recentActivities'
        ));
    }
}
