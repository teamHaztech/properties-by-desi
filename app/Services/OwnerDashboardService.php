<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Communication;
use App\Models\FollowUp;
use App\Models\Lead;
use App\Models\Property;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class OwnerDashboardService
{
    public function getRevenueOverview(): array
    {
        $wonLeads = Lead::where('status', 'closed_won')
            ->with('properties')
            ->get();

        $totalRevenue = 0;
        $monthlyRevenue = 0;
        $thisMonthStart = now()->startOfMonth();

        foreach ($wonLeads as $lead) {
            $dealValue = $lead->properties->sum('price') ?: $lead->budget_max ?: $lead->budget_min ?: 0;
            $totalRevenue += $dealValue;
            if ($lead->updated_at >= $thisMonthStart) {
                $monthlyRevenue += $dealValue;
            }
        }

        return [
            'total_revenue' => $totalRevenue,
            'monthly_revenue' => $monthlyRevenue,
            'total_deals' => Lead::where('status', 'closed_won')->count(),
            'monthly_deals' => Lead::where('status', 'closed_won')
                ->where('updated_at', '>=', $thisMonthStart)->count(),
            'avg_deal_size' => $wonLeads->count() > 0 ? $totalRevenue / $wonLeads->count() : 0,
            'deals_in_pipeline' => Lead::whereNotIn('status', ['closed_won', 'closed_lost', 'not_interested'])->count(),
            'pipeline_value' => Lead::whereNotIn('status', ['closed_won', 'closed_lost', 'not_interested'])
                ->sum('budget_max'),
        ];
    }

    public function getDailyLeadTrend(int $days = 30): array
    {
        $startDate = now()->subDays($days)->startOfDay();

        $dailyLeads = Lead::where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $labels = [];
        $data = [];
        for ($i = $days; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d M');
            $data[] = $dailyLeads[$date] ?? 0;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    public function getDailyConversionTrend(int $days = 30): array
    {
        $startDate = now()->subDays($days)->startOfDay();

        $won = Lead::where('status', 'closed_won')
            ->where('updated_at', '>=', $startDate)
            ->select(DB::raw('DATE(updated_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $lost = Lead::where('status', 'closed_lost')
            ->where('updated_at', '>=', $startDate)
            ->select(DB::raw('DATE(updated_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $labels = [];
        $wonData = [];
        $lostData = [];
        for ($i = $days; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d M');
            $wonData[] = $won[$date] ?? 0;
            $lostData[] = $lost[$date] ?? 0;
        }

        return ['labels' => $labels, 'won' => $wonData, 'lost' => $lostData];
    }

    public function getSourcePerformance(): array
    {
        return Lead::select(
                'source',
                DB::raw('count(*) as total'),
                DB::raw("SUM(CASE WHEN status = 'closed_won' THEN 1 ELSE 0 END) as won"),
                DB::raw("SUM(CASE WHEN status = 'closed_lost' THEN 1 ELSE 0 END) as lost"),
                DB::raw("SUM(CASE WHEN status NOT IN ('closed_won','closed_lost','not_interested') THEN 1 ELSE 0 END) as active"),
            )
            ->groupBy('source')
            ->get()
            ->map(function ($row) {
                return [
                    'source' => $row->source->label(),
                    'source_value' => $row->source->value,
                    'total' => $row->total,
                    'won' => $row->won,
                    'lost' => $row->lost,
                    'active' => $row->active,
                    'conversion_rate' => $row->total > 0 ? round(($row->won / $row->total) * 100, 1) : 0,
                ];
            })
            ->toArray();
    }

    public function getAgentLeaderboard(): array
    {
        return User::role(['sales_agent', 'manager'])
            ->withCount([
                'assignedLeads as total_leads',
                'assignedLeads as won_leads' => fn($q) => $q->where('status', 'closed_won'),
                'assignedLeads as lost_leads' => fn($q) => $q->where('status', 'closed_lost'),
                'assignedLeads as active_leads' => fn($q) => $q->whereNotIn('status', ['closed_won', 'closed_lost', 'not_interested']),
                'communications as total_calls' => fn($q) => $q->where('type', 'call'),
                'communications as total_comms',
                'followUps as pending_followups' => fn($q) => $q->where('status', 'pending'),
                'followUps as overdue_followups' => fn($q) => $q->where('status', 'pending')->where('scheduled_at', '<', now()),
            ])
            ->get()
            ->map(function ($agent) {
                $revenue = Lead::where('assigned_agent_id', $agent->id)
                    ->where('status', 'closed_won')
                    ->sum('budget_max');

                return [
                    'id' => $agent->id,
                    'name' => $agent->name,
                    'username' => $agent->username,
                    'total_leads' => $agent->total_leads,
                    'won' => $agent->won_leads,
                    'lost' => $agent->lost_leads,
                    'active' => $agent->active_leads,
                    'conversion_rate' => $agent->total_leads > 0
                        ? round(($agent->won_leads / $agent->total_leads) * 100, 1) : 0,
                    'total_comms' => $agent->total_comms,
                    'total_calls' => $agent->total_calls,
                    'pending_followups' => $agent->pending_followups,
                    'overdue_followups' => $agent->overdue_followups,
                    'revenue' => $revenue,
                ];
            })
            ->sortByDesc('won')
            ->values()
            ->toArray();
    }

    public function getDailyActivityLog(string $date = null): array
    {
        $date = $date ? Carbon::parse($date) : today();

        $leadsCreated = Lead::whereDate('created_at', $date)->count();
        $leadsWon = Lead::where('status', 'closed_won')->whereDate('updated_at', $date)->count();
        $leadsLost = Lead::where('status', 'closed_lost')->whereDate('updated_at', $date)->count();
        $communications = Communication::whereDate('created_at', $date)->count();
        $calls = Communication::whereDate('created_at', $date)->where('type', 'call')->count();
        $followUpsScheduled = FollowUp::whereDate('created_at', $date)->count();
        $followUpsCompleted = FollowUp::where('status', 'completed')->whereDate('completed_at', $date)->count();
        $siteVisits = Communication::whereDate('created_at', $date)->where('type', 'site_visit')->count();

        return [
            'date' => $date->format('Y-m-d'),
            'date_label' => $date->format('D, d M Y'),
            'leads_created' => $leadsCreated,
            'leads_won' => $leadsWon,
            'leads_lost' => $leadsLost,
            'total_communications' => $communications,
            'calls_made' => $calls,
            'follow_ups_scheduled' => $followUpsScheduled,
            'follow_ups_completed' => $followUpsCompleted,
            'site_visits' => $siteVisits,
        ];
    }

    public function getWeeklyBreakdown(): array
    {
        $days = [];
        for ($i = 6; $i >= 0; $i--) {
            $days[] = $this->getDailyActivityLog(now()->subDays($i)->format('Y-m-d'));
        }
        return $days;
    }

    public function getPropertyStats(): array
    {
        $byType = Property::select('type', 'status', DB::raw('count(*) as count'))
            ->groupBy('type', 'status')
            ->get()
            ->groupBy('type')
            ->map(function ($group) {
                return [
                    'available' => $group->where('status', 'available')->sum('count'),
                    'reserved' => $group->where('status', 'reserved')->sum('count'),
                    'sold' => $group->where('status', 'sold')->sum('count'),
                    'total' => $group->sum('count'),
                ];
            })
            ->toArray();

        $totalValue = Property::where('status', 'available')->sum('price');
        $soldValue = Property::where('status', 'sold')->sum('price');

        return [
            'by_type' => $byType,
            'total_inventory_value' => $totalValue,
            'total_sold_value' => $soldValue,
            'total_properties' => Property::count(),
        ];
    }

    public function getMonthlyTrend(int $months = 6): array
    {
        $labels = [];
        $newLeads = [];
        $closedWon = [];
        $closedLost = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $start = now()->subMonths($i)->startOfMonth();
            $end = now()->subMonths($i)->endOfMonth();
            $labels[] = $start->format('M Y');

            $newLeads[] = Lead::whereBetween('created_at', [$start, $end])->count();
            $closedWon[] = Lead::where('status', 'closed_won')
                ->whereBetween('updated_at', [$start, $end])->count();
            $closedLost[] = Lead::where('status', 'closed_lost')
                ->whereBetween('updated_at', [$start, $end])->count();
        }

        return [
            'labels' => $labels,
            'new_leads' => $newLeads,
            'closed_won' => $closedWon,
            'closed_lost' => $closedLost,
        ];
    }
}
