<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Notifications\FollowUpReminder;
use App\Notifications\AdminFollowUpEscalation;

class FollowUpController extends Controller
{
    /**
     * Get the date that should be used as the starting point
     * for the 3-day follow-up reminder.
     *
     * If the lead has an activity, use the latest activity.
     * If the lead has no activity, use the lead's registered date.
     */
    private function getFollowUpStartDate(Lead $lead)
    {
        $latestActivity = $lead->activities->first();

        if ($latestActivity) {
            return $latestActivity->created_at;
        }

        return $lead->created_at;
    }


    /**
     * Display follow-up management page.
     */
    public function index()
    {
        // Only admins can access follow-up management
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Leads that require a staff reminder TODAY
        |--------------------------------------------------------------------------
        */

        $remindersDueToday = Lead::with(['assignedStaff', 'activities'])
            ->whereNotNull('assigned_to')
            ->whereNotIn('status', ['Converted', 'Lost'])
            ->whereNull('follow_up_reminder_sent_at')
            ->get()
            ->filter(function ($lead) {

                $startDate = $this->getFollowUpStartDate($lead);

                if (!$startDate) {
                    return false;
                }

                return $startDate->lte(
                    now()->subDays(3)
                );
            });


        /*
        |--------------------------------------------------------------------------
        | Leads that will require a reminder TOMORROW
        |--------------------------------------------------------------------------
        */

        $remindersDueTomorrow = Lead::with(['assignedStaff', 'activities'])
            ->whereNotNull('assigned_to')
            ->whereNotIn('status', ['Converted', 'Lost'])
            ->whereNull('follow_up_reminder_sent_at')
            ->get()
            ->filter(function ($lead) {

                $startDate = $this->getFollowUpStartDate($lead);

                if (!$startDate) {
                    return false;
                }

                $tomorrow = now()
                    ->addDay()
                    ->startOfDay();

                $reminderDueDate = $startDate
                    ->copy()
                    ->addDays(3)
                    ->startOfDay();

                return $reminderDueDate->equalTo($tomorrow);
            });


        /*
        |--------------------------------------------------------------------------
        | Admin escalations due
        |--------------------------------------------------------------------------
        */

        $escalationsDue = Lead::with(['assignedStaff', 'activities'])
            ->whereNotNull('assigned_to')
            ->whereNotIn('status', ['Converted', 'Lost'])
            ->whereNotNull('follow_up_reminder_sent_at')
            ->whereNull('admin_escalation_sent_at')
            ->get()
            ->filter(function ($lead) {

                $latestActivity = $lead->activities->first();

                /*
                | If there is a new activity after the reminder,
                | the staff member has followed up, so no escalation.
                */
                if (
                    $latestActivity &&
                    $latestActivity->created_at >
                    $lead->follow_up_reminder_sent_at
                ) {
                    return false;
                }

                return $lead->follow_up_reminder_sent_at
                    ->lte(now()->subDay());
            });


        return view('follow-ups.index', compact(
            'remindersDueToday',
            'remindersDueTomorrow',
            'escalationsDue'
        ));
    }


    /**
     * Send a follow-up reminder to the assigned staff member.
     */
    public function sendReminder(Lead $lead)
    {
        // Only admins can send reminders
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Make sure the lead has an assigned staff member
        if (!$lead->assignedStaff) {
            return back()->with(
                'error',
                'This lead does not have an assigned staff member.'
            );
        }

        // Do not send reminders for converted or lost leads
        if (in_array($lead->status, ['Converted', 'Lost'])) {
            return back()->with(
                'error',
                'This lead no longer requires follow-up.'
            );
        }

        // Send reminder
        $lead->assignedStaff->notify(
            new FollowUpReminder($lead)
        );

        // Record reminder sent time
        $lead->update([
            'follow_up_reminder_sent_at' => now(),
            'admin_escalation_sent_at' => null,
        ]);

        return back()->with(
            'success',
            'Follow-up reminder sent to ' .
            $lead->assignedStaff->name .
            '.'
        );
    }


    /**
     * Send all reminders that are due today.
     */
    public function sendAllReminders()
    {
        // Only admins can send reminders
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $leads = Lead::with(['assignedStaff', 'activities'])
            ->whereNotNull('assigned_to')
            ->whereNotIn('status', ['Converted', 'Lost'])
            ->whereNull('follow_up_reminder_sent_at')
            ->get()
            ->filter(function ($lead) {

                $startDate = $this->getFollowUpStartDate($lead);

                if (!$startDate) {
                    return false;
                }

                return $startDate->lte(
                    now()->subDays(3)
                );
            });

        $count = 0;

        foreach ($leads as $lead) {

            if (!$lead->assignedStaff) {
                continue;
            }

            $lead->assignedStaff->notify(
                new FollowUpReminder($lead)
            );

            $lead->update([
                'follow_up_reminder_sent_at' => now(),
                'admin_escalation_sent_at' => null,
            ]);

            $count++;
        }

        return back()->with(
            'success',
            $count . ' follow-up reminder(s) sent successfully.'
        );
    }


    /**
     * Send an escalation email to the admin.
     */
    public function sendEscalation(Lead $lead)
    {
        // Only admins can send escalations
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        if (!$lead->assignedStaff) {
            return back()->with(
                'error',
                'This lead does not have an assigned staff member.'
            );
        }

        if (in_array($lead->status, ['Converted', 'Lost'])) {
            return back()->with(
                'error',
                'This lead no longer requires escalation.'
            );
        }

        // Send escalation to the current admin
        auth()->user()->notify(
            new AdminFollowUpEscalation($lead)
        );

        // Record escalation sent time
        $lead->update([
            'admin_escalation_sent_at' => now(),
        ]);

        return back()->with(
            'success',
            'Escalation email sent successfully.'
        );
    }
}