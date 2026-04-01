<?php

namespace App\Http\Controllers;

use App\Models\FollowUp;
use App\Models\Lead;
use Illuminate\Http\Request;

class FollowUpController extends Controller
{
    public function store(Request $request, Lead $lead)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_at' => 'required|date|after:now',
            'priority' => 'required|in:low,medium,high',
        ]);

        $lead->followUps()->create(array_merge($data, [
            'user_id' => auth()->id(),
        ]));

        return back()->with('success', 'Follow-up scheduled.');
    }

    public function complete(FollowUp $followUp)
    {
        $followUp->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return back()->with('success', 'Follow-up marked complete.');
    }

    public function cancel(FollowUp $followUp)
    {
        $followUp->update(['status' => 'cancelled']);

        return back()->with('success', 'Follow-up cancelled.');
    }
}
