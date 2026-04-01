<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService) {}

    public function index()
    {
        return view('dashboard', [
            'stats' => $this->dashboardService->getOverviewStats(),
            'leadsBySource' => $this->dashboardService->getLeadsBySource(),
            'leadsByStatus' => $this->dashboardService->getLeadsByStatus(),
            'agentPerformance' => $this->dashboardService->getAgentPerformance(),
            'recentLeads' => $this->dashboardService->getRecentLeads(),
            'todayFollowUps' => $this->dashboardService->getTodayFollowUps(),
            'overdueFollowUps' => $this->dashboardService->getOverdueFollowUps(),
        ]);
    }
}
