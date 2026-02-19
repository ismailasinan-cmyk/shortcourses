<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_applications' => \App\Models\Application::count(),
            'paid_applications' => \App\Models\Application::where('payment_status', 'PAID')->count(),
            'admitted_applications' => \App\Models\Application::where('admission_status', 'ADMITTED')->count(),
            'total_courses' => \App\Models\ShortCourse::count(),
        ];
        
        $recent_applications = \App\Models\Application::with(['course' => fn($q) => $q->withTrashed()])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_applications'));
    }
}
