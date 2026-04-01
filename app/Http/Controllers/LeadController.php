<?php

namespace App\Http\Controllers;

use App\Enums\LeadSource;
use App\Enums\LeadStatus;
use App\Http\Requests\Lead\StoreLeadRequest;
use App\Http\Requests\Lead\UpdateLeadRequest;
use App\Models\Lead;
use App\Models\User;
use App\Services\LeadService;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function __construct(protected LeadService $leadService) {}

    public function index(Request $request)
    {
        $leads = $this->leadService->getFilteredLeads($request->all());
        $agents = User::role('sales_agent')->get();

        return view('leads.index', [
            'leads' => $leads,
            'agents' => $agents,
            'statuses' => LeadStatus::cases(),
            'sources' => LeadSource::cases(),
            'filters' => $request->all(),
        ]);
    }

    public function pipeline()
    {
        $pipeline = $this->leadService->getLeadsByStatus();

        return view('leads.pipeline', [
            'pipeline' => $pipeline,
            'statuses' => LeadStatus::pipelineStatuses(),
        ]);
    }

    public function create()
    {
        $agents = User::role('sales_agent')->get();

        return view('leads.create', [
            'agents' => $agents,
            'sources' => LeadSource::cases(),
        ]);
    }

    public function store(StoreLeadRequest $request)
    {
        $duplicate = $this->leadService->checkDuplicate($request->phone);

        if ($duplicate) {
            return back()
                ->withInput()
                ->with('warning', "Duplicate lead found: {$duplicate->name} ({$duplicate->phone}) — Status: {$duplicate->status->label()}")
                ->with('duplicate_id', $duplicate->id);
        }

        $lead = $this->leadService->createLead($request->validated());

        return redirect()->route('leads.show', $lead)
            ->with('success', 'Lead created successfully.');
    }

    public function show(Lead $lead)
    {
        $lead->load([
            'assignedAgent',
            'client',
            'notes.user',
            'communications.user',
            'followUps.user',
            'properties',
            'activities.user',
        ]);

        return view('leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        $agents = User::role('sales_agent')->get();

        return view('leads.edit', [
            'lead' => $lead,
            'agents' => $agents,
            'statuses' => LeadStatus::cases(),
            'sources' => LeadSource::cases(),
        ]);
    }

    public function update(UpdateLeadRequest $request, Lead $lead)
    {
        $this->leadService->updateLead($lead, $request->validated());

        return redirect()->route('leads.show', $lead)
            ->with('success', 'Lead updated successfully.');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()->route('leads.index')
            ->with('success', 'Lead deleted successfully.');
    }

    public function updateStatus(Request $request, Lead $lead)
    {
        $request->validate(['status' => 'required|string']);
        $status = LeadStatus::from($request->status);

        $this->leadService->updateStatus($lead, $status);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'status' => $status->value]);
        }

        return back()->with('success', 'Status updated.');
    }

    public function quickCreate()
    {
        $agents = User::role('sales_agent')->get();

        return view('leads.quick-create', ['agents' => $agents, 'sources' => LeadSource::cases()]);
    }

    public function quickStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'source' => 'required|string',
            'assigned_agent_id' => 'nullable|exists:users,id',
        ]);

        $duplicate = $this->leadService->checkDuplicate($data['phone']);
        if ($duplicate) {
            return back()
                ->withInput()
                ->with('warning', "Duplicate: {$duplicate->name} ({$duplicate->phone})");
        }

        $lead = $this->leadService->createLead($data);

        return redirect()->route('leads.show', $lead)
            ->with('success', 'Lead created quickly!');
    }

    public function addNote(Request $request, Lead $lead)
    {
        $request->validate(['content' => 'required|string']);

        $lead->notes()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
            'is_pinned' => $request->boolean('is_pinned'),
        ]);

        return back()->with('success', 'Note added.');
    }

    public function addCommunication(Request $request, Lead $lead)
    {
        $data = $request->validate([
            'type' => 'required|in:call,whatsapp,email,sms,meeting,site_visit,other',
            'direction' => 'required|in:inbound,outbound',
            'summary' => 'required|string',
            'duration_minutes' => 'nullable|integer|min:0',
        ]);

        $lead->communications()->create(array_merge($data, [
            'user_id' => auth()->id(),
        ]));

        $lead->update(['last_contacted_at' => now()]);

        return back()->with('success', 'Communication logged.');
    }
}
