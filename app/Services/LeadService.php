<?php

namespace App\Services;

use App\Enums\LeadStatus;
use App\Models\ActivityLog;
use App\Models\Lead;
use App\Models\User;
use App\Notifications\LeadAssignedNotification;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeadService
{
    public function getFilteredLeads(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Lead::with(['assignedAgent'])
            ->search($filters['search'] ?? null);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['source'])) {
            $query->where('source', $filters['source']);
        }

        if (!empty($filters['assigned_agent_id'])) {
            $query->where('assigned_agent_id', $filters['assigned_agent_id']);
        }

        if (!empty($filters['urgency'])) {
            $query->where('urgency', $filters['urgency']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDir = $filters['sort_dir'] ?? 'desc';

        return $query->orderBy($sortBy, $sortDir)->paginate($perPage);
    }

    public function getLeadsByStatus(): array
    {
        $statuses = LeadStatus::pipelineStatuses();
        $result = [];

        foreach ($statuses as $status) {
            $result[$status->value] = Lead::with('assignedAgent')
                ->where('status', $status)
                ->orderBy('updated_at', 'desc')
                ->limit(50)
                ->get();
        }

        return $result;
    }

    public function createLead(array $data): Lead
    {
        return DB::transaction(function () use ($data) {
            $lead = Lead::create($data);

            $this->logActivity($lead, 'created');
            $this->notifyAssignedAgent($lead);

            return $lead;
        });
    }

    public function updateLead(Lead $lead, array $data): Lead
    {
        return DB::transaction(function () use ($lead, $data) {
            $oldAgentId = $lead->assigned_agent_id;
            $oldStatus = $lead->status;
            $lead->update($data);

            if (isset($data['status']) && $oldStatus !== $lead->status) {
                $this->logActivity($lead, 'status_changed', [
                    'from' => $oldStatus->value,
                    'to' => $lead->status->value,
                ]);
            } else {
                $this->logActivity($lead, 'updated');
            }

            // Notify new agent if reassigned
            if (isset($data['assigned_agent_id']) && $data['assigned_agent_id'] != $oldAgentId) {
                $this->notifyAssignedAgent($lead->fresh());
            }

            return $lead->fresh();
        });
    }

    protected function notifyAssignedAgent(Lead $lead): void
    {
        if (!$lead->assigned_agent_id) return;

        $agent = User::find($lead->assigned_agent_id);
        if ($agent && Auth::user()) {
            $agent->notify(new LeadAssignedNotification($lead, Auth::user()));
        }
    }

    public function updateStatus(Lead $lead, LeadStatus $status): Lead
    {
        $old = $lead->status;
        $lead->update(['status' => $status]);

        $this->logActivity($lead, 'status_changed', [
            'from' => $old->value,
            'to' => $status->value,
        ]);

        return $lead;
    }

    public function checkDuplicate(string $phone): ?Lead
    {
        return Lead::where('phone', $phone)->first();
    }

    public function convertToClient(Lead $lead, array $clientData): Lead
    {
        return DB::transaction(function () use ($lead, $clientData) {
            $clientData['lead_id'] = $lead->id;
            $clientData['name'] = $clientData['name'] ?? $lead->name;
            $clientData['phone'] = $clientData['phone'] ?? $lead->phone;
            $clientData['email'] = $clientData['email'] ?? $lead->email;

            $lead->client()->create($clientData);
            $lead->update(['is_converted' => true]);

            $this->logActivity($lead, 'converted_to_client');

            return $lead->fresh(['client']);
        });
    }

    protected function logActivity(Lead $lead, string $action, ?array $changes = null): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => Lead::class,
            'subject_id' => $lead->id,
            'action' => $action,
            'changes' => $changes,
        ]);
    }
}
