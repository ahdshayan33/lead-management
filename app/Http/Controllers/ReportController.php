<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display reports and analytics.
     */
    public function index()
    {
        // Only admins can access reports
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Overall Lead Statistics
        |--------------------------------------------------------------------------
        */

        $totalLeads = Lead::count();

        $newLeads = Lead::where('status', 'New Lead')->count();

        $convertedLeads = Lead::where('status', 'Converted')->count();

        $lostLeads = Lead::where('status', 'Lost')->count();

        $pendingFollowUps = Lead::whereNotNull('follow_up_date')
            ->whereDate('follow_up_date', '>=', now()->toDateString())
            ->whereNotIn('status', ['Converted', 'Lost'])
            ->count();

        $overdueFollowUps = Lead::whereNotNull('follow_up_date')
            ->whereDate('follow_up_date', '<', now()->toDateString())
            ->whereNotIn('status', ['Converted', 'Lost'])
            ->count();

        $conversionRate = $totalLeads > 0
            ? round(($convertedLeads / $totalLeads) * 100, 1)
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Total Conversion Revenue
        |--------------------------------------------------------------------------
        */

        $totalRevenue = Lead::where('status', 'Converted')
            ->sum('conversion_value');


        /*
        |--------------------------------------------------------------------------
        | Communication Preference Analysis
        |--------------------------------------------------------------------------
        */

        $emailLeads = Lead::where('communication_method', 'email')->count();

        $whatsappLeads = Lead::where('communication_method', 'whatsapp')->count();


        /*
        |--------------------------------------------------------------------------
        | Most Requested Products / Services
        |--------------------------------------------------------------------------
        */

        $topProducts = Lead::select('product_service')
            ->selectRaw('COUNT(*) as total')
            ->whereNotNull('product_service')
            ->groupBy('product_service')
            ->orderByDesc('total')
            ->take(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Staff Performance
        |--------------------------------------------------------------------------
        */

        $staff = User::where('role', 'staff')
            ->orderBy('name')
            ->get();

        $staffPerformance = $staff->map(function ($member) {

            /*
            | Assigned leads
            */

            $assignedLeads = Lead::where('assigned_to', $member->id)
                ->count();

            /*
            | Converted leads
            */

            $convertedLeads = Lead::where('assigned_to', $member->id)
                ->where('status', 'Converted')
                ->count();

            /*
            | Pending follow-ups
            */

            $pendingFollowUps = Lead::where('assigned_to', $member->id)
                ->whereNotNull('follow_up_date')
                ->whereDate('follow_up_date', '>=', now()->toDateString())
                ->whereNotIn('status', ['Converted', 'Lost'])
                ->count();

            /*
            | Overdue follow-ups
            */

            $overdueFollowUps = Lead::where('assigned_to', $member->id)
                ->whereNotNull('follow_up_date')
                ->whereDate('follow_up_date', '<', now()->toDateString())
                ->whereNotIn('status', ['Converted', 'Lost'])
                ->count();

            /*
            | Completed follow-ups
            |
            | We count activities where a follow-up date
            | was recorded.
            */

            $completedFollowUps = LeadActivity::where('user_id', $member->id)
                ->whereNotNull('follow_up_date')
                ->count();

            /*
            | Conversion percentage
            */

            $conversionPercentage = $assignedLeads > 0
                ? round(($convertedLeads / $assignedLeads) * 100, 1)
                : 0;

            /*
            | Average response time
            |
            | Response time = lead creation → first activity.
            */

            $responseTimes = [];

            $memberLeads = Lead::where('assigned_to', $member->id)
                ->get();

            foreach ($memberLeads as $lead) {

                $firstActivity = LeadActivity::where('lead_id', $lead->id)
                    ->orderBy('created_at')
                    ->first();

                if ($firstActivity) {

                    $responseTimes[] = $lead->created_at
                        ->diffInMinutes($firstActivity->created_at);
                }
            }

            $averageResponseMinutes = count($responseTimes) > 0
                ? round(array_sum($responseTimes) / count($responseTimes))
                : null;

            /*
            | Convert minutes to readable format
            */

            if ($averageResponseMinutes === null) {

                $averageResponseTime = 'No data';

            } elseif ($averageResponseMinutes < 60) {

                $averageResponseTime = $averageResponseMinutes . ' min';

            } else {

                $hours = floor($averageResponseMinutes / 60);

                $minutes = $averageResponseMinutes % 60;

                $averageResponseTime = $hours . 'h ' . $minutes . 'm';
            }

            return [
                'name' => $member->name,
                'assigned_leads' => $assignedLeads,
                'completed_follow_ups' => $completedFollowUps,
                'converted_leads' => $convertedLeads,
                'pending_follow_ups' => $pendingFollowUps,
                'overdue_follow_ups' => $overdueFollowUps,
                'conversion_percentage' => $conversionPercentage,
                'average_response_time' => $averageResponseTime,
            ];
        });


        /*
        |--------------------------------------------------------------------------
        | Lead Status Analysis
        |--------------------------------------------------------------------------
        */

        $statusCounts = [
            'New Lead' => Lead::where('status', 'New Lead')->count(),
            'Contacted' => Lead::where('status', 'Contacted')->count(),
            'Interested' => Lead::where('status', 'Interested')->count(),
            'Follow-up Required' => Lead::where('status', 'Follow-up Required')->count(),
            'Quotation Sent' => Lead::where('status', 'Quotation Sent')->count(),
            'Converted' => Lead::where('status', 'Converted')->count(),
            'Lost' => Lead::where('status', 'Lost')->count(),
        ];


        return view('reports.index', compact(
            'totalLeads',
            'newLeads',
            'convertedLeads',
            'lostLeads',
            'pendingFollowUps',
            'overdueFollowUps',
            'conversionRate',
            'totalRevenue',
            'emailLeads',
            'whatsappLeads',
            'topProducts',
            'staffPerformance',
            'statusCounts'
        ));
    }
}