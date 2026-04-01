<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lead\StoreLeadRequest;
use App\Http\Requests\Lead\UpdateLeadRequest;
use App\Models\Lead;
use App\Services\LeadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadApiController extends Controller
{
    public function __construct(protected LeadService $leadService) {}

    public function index(Request $request): JsonResponse
    {
        $leads = $this->leadService->getFilteredLeads($request->all());

        return response()->json($leads);
    }

    public function store(StoreLeadRequest $request): JsonResponse
    {
        $duplicate = $this->leadService->checkDuplicate($request->phone);

        if ($duplicate && !$request->boolean('force')) {
            return response()->json([
                'warning' => 'Duplicate lead found',
                'duplicate' => $duplicate,
            ], 409);
        }

        $lead = $this->leadService->createLead($request->validated());

        return response()->json($lead, 201);
    }

    public function show(Lead $lead): JsonResponse
    {
        $lead->load([
            'assignedAgent',
            'client',
            'notes.user',
            'communications.user',
            'followUps',
            'properties',
        ]);

        return response()->json($lead);
    }

    public function update(UpdateLeadRequest $request, Lead $lead): JsonResponse
    {
        $lead = $this->leadService->updateLead($lead, $request->validated());

        return response()->json($lead);
    }

    public function destroy(Lead $lead): JsonResponse
    {
        $lead->delete();

        return response()->json(null, 204);
    }

    public function updateStatus(Request $request, Lead $lead): JsonResponse
    {
        $request->validate(['status' => 'required|string']);
        $status = \App\Enums\LeadStatus::from($request->status);

        $this->leadService->updateStatus($lead, $status);

        return response()->json(['status' => $status->value]);
    }
}
