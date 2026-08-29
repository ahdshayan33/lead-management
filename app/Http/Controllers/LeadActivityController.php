<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\User;
use Illuminate\Http\Request;

class LeadActivityController extends Controller
{
    /**
     * Display all lead activities for admins.
     */
    public function index(Request $request)
    {
        
        // Only admins can access the activity monitoring page
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $query = LeadActivity::with([
            'lead',
            'user',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Search by lead name
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->whereHas('lead', function ($q) use ($search) {

                $q->where('name', 'like', '%' . $search . '%');

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter by staff member
        |--------------------------------------------------------------------------
        */

        if ($request->filled('user_id')) {

            $query->where('user_id', $request->user_id);
        }

        /*
        |--------------------------------------------------------------------------
        | Filter by activity type
        |--------------------------------------------------------------------------
        */

        if ($request->filled('activity_type')) {

            $query->where(
                'activity_type',
                $request->activity_type
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filter by lead status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->whereHas('lead', function ($q) use ($request) {

                $q->where('status', $request->status);

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter by date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date')) {

            $query->whereDate(
                'created_at',
                $request->date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Get filter options
        |--------------------------------------------------------------------------
        */

        $staff = User::where('role', 'staff')
            ->orderBy('name')
            ->get();

        $activityTypes = LeadActivity::query()
            ->whereNotNull('activity_type')
            ->select('activity_type')
            ->distinct()
            ->orderBy('activity_type')
            ->pluck('activity_type');

        $statuses = [
            'New Lead',
            'Contacted',
            'Interested',
            'Follow-up Required',
            'Quotation Sent',
            'Converted',
            'Lost',
        ];

        /*
        |--------------------------------------------------------------------------
        | Get activities
        |--------------------------------------------------------------------------
        */

        $activities = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('leads.activities', compact(
            'activities',
            'staff',
            'activityTypes',
            'statuses'
        ));
    }


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

        $validated['activity_type'] = match ($validated['communication_method']) {
            'WhatsApp' => 'whatsapp_contact',
            'Email' => 'email_sent',
            'Call' => 'call',
            'Meeting' => 'meeting',
            'Other' => 'other',
        };

        $validated['lead_id'] = $lead->id;
        $validated['user_id'] = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | Record the activity
        |--------------------------------------------------------------------------
        */

        LeadActivity::create($validated);

        /*
        |--------------------------------------------------------------------------
        | Update follow-up date
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['follow_up_date'])) {

            $lead->update([
                'follow_up_date' => $validated['follow_up_date'],
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Reset reminder and escalation cycle
        |--------------------------------------------------------------------------
        */

        $lead->update([
            'follow_up_reminder_sent_at' => null,
            'admin_escalation_sent_at' => null,
        ]);

        return redirect()
            ->route('leads.show', $lead)
            ->with('success', 'Activity added successfully!');
    }
}