<?php

namespace App\Services;

use App\Enums\LeadStatus;
use App\Models\Communication;
use App\Models\FollowUp;
use App\Models\Lead;
use App\Models\Property;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getOverviewStats(): array
    {
        return [
            'total_leads' => Lead::count(),
            'new_leads_today' => Lead::whereDate('created_at', today())->count(),
            'new_leads_week' => Lead::where('created_at', '>=', now()->startOfWeek())->count(),
            'active_leads' => Lead::whereNotIn('status', ['closed_won', 'closed_lost'])->count(),
            'closed_won' => Lead::where('status', LeadStatus::ClosedWon)->count(),
            'closed_lost' => Lead::where('status', LeadStatus::ClosedLost)->count(),
            'conversion_rate' => $this->getConversionRate(),
            'properties_available' => Property::where('status', 'available')->count(),
            'properties_total' => Property::count(),
            'follow_ups_today' => FollowUp::today()->pending()->count(),
            'follow_ups_overdue' => FollowUp::overdue()->count(),
        ];
    }

    public function getLeadsBySource(): array
    {
        return Lead::select('source', DB::raw('count(*) as count'))
            ->groupBy('source')
            ->pluck('count', 'source')
            ->toArray();
    }

    public function getLeadsByStatus(): array
    {
        return Lead::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    public function getAgentPerformance(): array
    {
        return Lead::select(
                'assigned_agent_id',
                DB::raw('count(*) as total_leads'),
                DB::raw("SUM(CASE WHEN status = 'closed_won' THEN 1 ELSE 0 END) as won"),
                DB::raw("SUM(CASE WHEN status = 'closed_lost' THEN 1 ELSE 0 END) as lost"),
            )
            ->whereNotNull('assigned_agent_id')
            ->groupBy('assigned_agent_id')
            ->with('assignedAgent:id,name')
            ->get()
            ->map(function ($row) {
                return [
                    'agent' => $row->assignedAgent->name ?? 'Unassigned',
                    'total' => $row->total_leads,
                    'won' => $row->won,
                    'lost' => $row->lost,
                    'rate' => $row->total_leads > 0
                        ? round(($row->won / $row->total_leads) * 100, 1)
                        : 0,
                ];
            })
            ->toArray();
    }

    public function getRecentLeads(int $limit = 10)
    {
        return Lead::with('assignedAgent')
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getTodayFollowUps()
    {
        return FollowUp::with(['lead', 'user'])
            ->today()
            ->pending()
            ->orderBy('scheduled_at')
            ->get();
    }

    public function getOverdueFollowUps()
    {
        return FollowUp::with(['lead', 'user'])
            ->overdue()
            ->orderBy('scheduled_at')
            ->get();
    }

    protected function getConversionRate(): float
    {
        $total = Lead::count();
        if ($total === 0) return 0;

        $won = Lead::where('status', LeadStatus::ClosedWon)->count();
        return round(($won / $total) * 100, 1);
    }
}
