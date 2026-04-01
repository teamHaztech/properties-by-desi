<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;

class DashboardApiController extends Controller
{
    public function __construct(protected DashboardService $dashboardService) {}

    public function stats(): JsonResponse
    {
        return response()->json([
            'overview' => $this->dashboardService->getOverviewStats(),
            'leads_by_source' => $this->dashboardService->getLeadsBySource(),
            'leads_by_status' => $this->dashboardService->getLeadsByStatus(),
            'agent_performance' => $this->dashboardService->getAgentPerformance(),
        ]);
    }
}
