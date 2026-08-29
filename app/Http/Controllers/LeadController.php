<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\User;
use App\Notifications\NewLeadAssigned;
use App\Notifications\LeadRegisteredNotification;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Lead::with('assignedStaff');

        // Staff can only see leads assigned to themselves
        if ($user->role === 'staff') {
            $query->where('assigned_to', $user->id);
        }

        // Search by name, email or phone
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('phone', 'like', '%' . $search . '%');

            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Admin can filter by assigned staff
        if (
            $user->role === 'admin' &&
            $request->filled('assigned_to')
        ) {
            $query->where('assigned_to', $request->assigned_to);
        }

        // Get staff list for admin filter
        $staff = [];

        if ($user->role === 'admin') {
            $staff = User::where('role', 'staff')
                ->orderBy('name')
                ->get();
        }

        $leads = $query
            ->latest()
            ->get();

        return view('leads.index', compact(
            'leads',
            'staff'
        ));
    }

    /**
     * Display the lead registration form.
     */
    public function create()
    {
        // Only admins can register new leads
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        return view('leads.create');
    }

        /**
     * Store a newly registered lead.
     */
    public function store(Request $request)
    {
        // Only admins can register new leads
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:30',
            'product_service' => 'required|string|max:255',
            'lead_source' => 'nullable|string|max:255',
            'communication_method' => 'required|in:email,whatsapp',
            'requirements' => 'nullable|string',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Automatically assign lead to staff member with the fewest active leads
        |--------------------------------------------------------------------------
        */

        $staff = User::where('role', 'staff')
            ->withCount([
                'assignedLeads as active_leads_count' => function ($query) {
                    $query->whereNotIn('status', ['Converted', 'Lost']);
                }
            ])
            ->orderBy('active_leads_count')
            ->orderBy('id')
            ->first();

        // Make sure at least one staff member exists
        if (!$staff) {
            return back()
                ->withInput()
                ->withErrors([
                    'name' => 'No staff members are available to assign this lead.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Create the lead
        |--------------------------------------------------------------------------
        */

        $validated['status'] = 'New Lead';
        $validated['assigned_to'] = $staff->id;

        $lead = Lead::create($validated);

        /*
        |--------------------------------------------------------------------------
        | Send email to the lead
        |--------------------------------------------------------------------------
        */

        if ($lead->communication_method === 'email' && $lead->email) {
            \Illuminate\Support\Facades\Notification::route(
                'mail',
                $lead->email
            )->notify(
                new LeadRegisteredNotification($lead)
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Notify assigned staff member
        |--------------------------------------------------------------------------
        */

        $staff->notify(
            new NewLeadAssigned($lead)
        );

        /*
        |--------------------------------------------------------------------------
        | Record lead creation activity
        |--------------------------------------------------------------------------
        */

        LeadActivity::create([
            'lead_id' => $lead->id,
            'user_id' => auth()->id(),
            'activity_type' => 'lead_created',
            'communication_method' => null,
            'notes' => 'Lead registered by ' . auth()->user()->name,
        ]);

        return redirect()
            ->route('leads.create')
            ->with('success', 'Lead registered successfully and assigned to ' . $staff->name . '.');
    }

    /**
     * Display a specific lead.
     */
    public function show(Lead $lead)
    {
        // Staff can only view leads assigned to themselves
        if (
            auth()->user()->role === 'staff' &&
            $lead->assigned_to !== auth()->id()
        ) {
            abort(403);
        }

        $lead->load([
            'assignedStaff',
            'activities.user'
        ]);

        return view('leads.show', compact('lead'));
    }

    /**
     * Display the edit form.
     */
    public function edit(Lead $lead)
    {
        // Staff can only edit leads assigned to themselves
        if (
            auth()->user()->role === 'staff' &&
            $lead->assigned_to !== auth()->id()
        ) {
            abort(403);
        }

        $staff = User::where('role', 'staff')
            ->orderBy('name')
            ->get();

        return view('leads.edit', compact('lead', 'staff'));
    }


    /**
     * Update an existing lead.
     */
    public function update(Request $request, Lead $lead)
    {
        // Staff can only update leads assigned to themselves
        if (
            auth()->user()->role === 'staff' &&
            $lead->assigned_to !== auth()->id()
        ) {
            abort(403);
        }

        $oldStatus = $lead->status;

      
        // Converted leads are final and cannot be moved back to another status
        if ($oldStatus === 'Converted') {
            return redirect()
                ->route('leads.show', $lead)
                ->with('error', 'This lead has already been converted and cannot be changed.');
        }


        /*
        |--------------------------------------------------------------------------
        | ADMIN UPDATE
        |--------------------------------------------------------------------------
        */

        if (auth()->user()->role === 'admin') {

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'required|string|max:30',
                'product_service' => 'required|string|max:255',
                'lead_source' => 'nullable|string|max:255',
                'communication_method' => 'required|in:email,whatsapp',
                'requirements' => 'nullable|string',

                'status' => 'required|in:New Lead,Contacted,Interested,Follow-up Required,Quotation Sent,Converted,Lost',

                'follow_up_date' => 'nullable|date',

                'priority' => 'required|in:Low,Medium,High',

                'assigned_to' => 'nullable|exists:users,id',

                'conversion_value' => 'nullable|numeric|min:0',

                'conversion_notes' => 'nullable|string',
            ]);

            // Reset reminder if follow-up date has changed
            if ($lead->follow_up_date != ($validated['follow_up_date'] ?? null)) {
                $validated['follow_up_reminder_sent_at'] = null;
            }

            $lead->update($validated);
        }

        /*
        |--------------------------------------------------------------------------
        | STAFF UPDATE
        |--------------------------------------------------------------------------
        */

        else {

            // Staff can ONLY update these fields
            $validated = $request->validate([
                'status' => 'required|in:New Lead,Contacted,Interested,Follow-up Required,Quotation Sent,Converted,Lost',

                'follow_up_date' => 'nullable|date',

                'priority' => 'required|in:Low,Medium,High',

                'requirements' => 'nullable|string',

                'conversion_value' => 'nullable|numeric|min:0',

                'conversion_notes' => 'nullable|string',
            ]);

            // Reset reminder if follow-up date has changed
            if ($lead->follow_up_date != ($validated['follow_up_date'] ?? null)) {
                $validated['follow_up_reminder_sent_at'] = null;
            }

            $lead->update($validated);
        }

        /*
        |--------------------------------------------------------------------------
        | CONVERSION RECORDING
        |--------------------------------------------------------------------------
        */

        if ($oldStatus !== 'Converted' && $lead->status === 'Converted') {

            $lead->update([
                'converted_at' => now(),
            ]);

            LeadActivity::create([
                'lead_id' => $lead->id,
                'user_id' => auth()->id(),
                'activity_type' => 'lead_converted',
                'communication_method' => null,
                'notes' => 'Lead converted by ' . auth()->user()->name
                    . ($lead->conversion_value !== null
                        ? '. Conversion value: ' . $lead->conversion_value
                        : ''),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS CHANGE RECORDING
        |--------------------------------------------------------------------------
        */

        if (
            $oldStatus !== $lead->status &&
            $lead->status !== 'Converted'
        ) {

            LeadActivity::create([
                'lead_id' => $lead->id,
                'user_id' => auth()->id(),
                'activity_type' => 'status_changed',
                'communication_method' => null,
                'notes' => "Status changed from {$oldStatus} to {$lead->status}.",
            ]);
        }

        return redirect()
            ->route('leads.show', $lead)
            ->with('success', 'Lead updated successfully!');
    }



        /**
     * Display the dashboard.
     */
    public function dashboard()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {

            $query = Lead::query();

        } else {

            $query = Lead::where('assigned_to', $user->id);
        }

        $totalLeads = (clone $query)->count();

        $newLeads = (clone $query)
            ->where('status', 'New Lead')
            ->count();

        $contactedLeads = (clone $query)
            ->where('status', 'Contacted')
            ->count();

        $followUpLeads = (clone $query)
            ->where('status', 'Follow-up Required')
            ->count();

        $convertedLeads = (clone $query)
            ->where('status', 'Converted')
            ->count();

        $lostLeads = (clone $query)
            ->where('status', 'Lost')
            ->count();

        $highPriorityLeads = (clone $query)
            ->where('priority', 'High')
            ->count();

        $recentLeads = (clone $query)
            ->with('assignedStaff')
            ->latest()
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Follow-up Management
        |--------------------------------------------------------------------------
        */

        // Overdue follow-ups
        $overdueFollowUps = (clone $query)
            ->with('assignedStaff')
            ->whereNotNull('follow_up_date')
            ->whereDate('follow_up_date', '<', now()->toDateString())
            ->whereNotIn('status', ['Converted', 'Lost'])
            ->orderBy('follow_up_date')
            ->get();


        // Upcoming follow-ups
        $upcomingFollowUps = (clone $query)
            ->with('assignedStaff')
            ->whereNotNull('follow_up_date')
            ->whereDate('follow_up_date', '>=', now()->toDateString())
            ->whereNotIn('status', ['Converted', 'Lost'])
            ->orderBy('follow_up_date')
            ->take(10)
            ->get();


        return view('dashboard', compact(
            'totalLeads',
            'newLeads',
            'contactedLeads',
            'followUpLeads',
            'convertedLeads',
            'lostLeads',
            'highPriorityLeads',
            'recentLeads',
            'overdueFollowUps',
            'upcomingFollowUps'
        ));
}







}