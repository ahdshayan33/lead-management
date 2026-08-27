<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadActivity;
use Illuminate\Http\Request;

class LeadActivityController extends Controller
{
    /**
     * Store a new activity for a lead.
     */
    public function store(Request $request, Lead $lead)
    {
        // Staff can only add activities to leads assigned to them
        if (
            auth()->user()->role === 'staff' &&
            $lead->assigned_to !== auth()->id()
        ) {
            abort(403);
        }

        $validated = $request->validate([
            'communication_method' => 'required|in:Call,WhatsApp,Email,Meeting,Other',
            'notes' => 'required|string',
            'follow_up_date' => 'nullable|date',
        ]);

        $validated['lead_id'] = $lead->id;
        $validated['user_id'] = auth()->id();

        LeadActivity::create($validated);

        // If a follow-up date was entered, update the lead's follow-up date
        if (!empty($validated['follow_up_date'])) {
            $lead->update([
                'follow_up_date' => $validated['follow_up_date'],
            ]);
        }

        return redirect()
            ->route('leads.show', $lead)
            ->with('success', 'Activity added successfully!');
    }
}