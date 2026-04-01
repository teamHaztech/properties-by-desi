<?php

namespace App\Http\Controllers;

use App\Services\OwnerDashboardService;
use Illuminate\Http\Request;

class OwnerDashboardController extends Controller
{
    public function __construct(protected OwnerDashboardService $service) {}

    public function index(Request $request)
    {
        $dateRange = $request->get('range', '30');

        return view('owner.dashboard', [
            'revenue' => $this->service->getRevenueOverview(),
            'dailyLeadTrend' => $this->service->getDailyLeadTrend((int) $dateRange),
            'conversionTrend' => $this->service->getDailyConversionTrend((int) $dateRange),
            'sourcePerformance' => $this->service->getSourcePerformance(),
            'agentLeaderboard' => $this->service->getAgentLeaderboard(),
            'weeklyBreakdown' => $this->service->getWeeklyBreakdown(),
            'propertyStats' => $this->service->getPropertyStats(),
            'monthlyTrend' => $this->service->getMonthlyTrend(),
            'todayActivity' => $this->service->getDailyActivityLog(),
            'dateRange' => $dateRange,
        ]);
    }

    public function dailyReport(Request $request)
    {
        $date = $request->get('date', today()->format('Y-m-d'));

        return view('owner.daily-report', [
            'activity' => $this->service->getDailyActivityLog($date),
            'date' => $date,
        ]);
    }
}
