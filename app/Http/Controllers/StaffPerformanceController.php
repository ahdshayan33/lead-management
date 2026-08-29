<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lead;
use App\Models\LeadActivity;

class StaffPerformanceController extends Controller
{
    public function index()
    {
        // Only admins can view staff performance
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $staff = User::where('role', 'staff')
            ->withCount([
                'assignedLeads as total_leads',
                'assignedLeads as converted_leads' => function ($query) {
                    $query->where('status', 'Converted');
                },
                'assignedLeads as pending_followups' => function ($query) {
                    $query->whereNotNull('follow_up_date')
                        ->whereDate('follow_up_date', '>=', now()->toDateString())
                        ->whereNotIn('status', ['Converted', 'Lost']);
                },
                'assignedLeads as overdue_followups' => function ($query) {
                    $query->whereNotNull('follow_up_date')
                        ->whereDate('follow_up_date', '<', now()->toDateString())
                        ->whereNotIn('status', ['Converted', 'Lost']);
                },
            ])
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Calculate performance data for each staff member
        |--------------------------------------------------------------------------
        */

        foreach ($staff as $member) {

            // Number of completed follow-ups
            $completedFollowUps = LeadActivity::where('user_id', $member->id)
                ->whereIn('activity_type', [
                    'call',
                    'email_sent',
                ])
                ->whereNotNull('follow_up_date')
                ->count();

            $member->completed_followups = $completedFollowUps;

            // Conversion percentage
            if ($member->total_leads > 0) {
                $member->conversion_percentage = round(
                    ($member->converted_leads / $member->total_leads) * 100,
                    1
                );
            } else {
                $member->conversion_percentage = 0;
            }

            /*
            |--------------------------------------------------------------------------
            | Average response time
            |--------------------------------------------------------------------------
            */

            $leadIds = Lead::where('assigned_to', $member->id)
                ->pluck('id');

            $responseTimes = [];

            foreach ($leadIds as $leadId) {

                $lead = Lead::find($leadId);

                if (!$lead) {
                    continue;
                }

                $firstResponse = LeadActivity::where('lead_id', $leadId)
                    ->where('user_id', $member->id)
                    ->whereIn('activity_type', [
                        'call',
                        'email_sent',
                    ])
                    ->oldest()
                    ->first();

                if ($firstResponse) {

                    $responseTimes[] = $lead->created_at
                        ->diffInMinutes($firstResponse->created_at);
                }
            }

            if (count($responseTimes) > 0) {

                $averageMinutes = round(
                    array_sum($responseTimes) / count($responseTimes)
                );

                if ($averageMinutes < 60) {

                    $member->average_response_time =
                        $averageMinutes . ' min';

                } else {

                    $hours = floor($averageMinutes / 60);
                    $minutes = $averageMinutes % 60;

                    $member->average_response_time =
                        $hours . 'h ' . $minutes . 'm';
                }

            } else {

                $member->average_response_time = 'N/A';
            }
        }

        return view(
            'staff-performance.index',
            compact('staff')
        );
    }
}