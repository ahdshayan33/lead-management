<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Lead;
use App\Models\User;
use App\Notifications\FollowUpReminder;
use App\Notifications\AdminFollowUpEscalation;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');



Schedule::call(function () {

    $leads = Lead::with('assignedStaff')
        ->whereNotNull('assigned_to')
        ->whereNotIn('status', ['Converted', 'Lost'])
        ->get();

    foreach ($leads as $lead) {

        $assignedStaff = $lead->assignedStaff;

        if (!$assignedStaff) {
            continue;
        }

        // Get the most recent activity for this lead
        $latestActivity = $lead->activities()
            ->latest('created_at')
            ->first();

        if (!$latestActivity) {
            continue;
        }

        $lastActivityDate = $latestActivity->created_at;


        /*
        |--------------------------------------------------------------------------
        | STAFF FOLLOW-UP REMINDER
        |--------------------------------------------------------------------------
        |
        | If there has been no activity for 3 days,
        | send a reminder to the assigned staff member.
        |
        */

        if (
            $lastActivityDate->lte(now()->subDays(3)) &&
            !$lead->follow_up_reminder_sent_at
        ) {

            $assignedStaff->notify(
                new FollowUpReminder($lead)
            );

            $lead->update([
                'follow_up_reminder_sent_at' => now(),
                'admin_escalation_sent_at' => null,
            ]);

            continue;
        }


        /*
        |--------------------------------------------------------------------------
        | ADMIN ESCALATION
        |--------------------------------------------------------------------------
        |
        | If 1 day has passed since the staff reminder
        | and there is still no new activity,
        | notify the admin.
        |
        */

        if (
            $lead->follow_up_reminder_sent_at &&
            !$lead->admin_escalation_sent_at &&
            $lead->follow_up_reminder_sent_at->lte(now()->subDay())
        ) {

            $admin = User::where('role', 'admin')->first();

            if ($admin) {

                $admin->notify(
                    new AdminFollowUpEscalation($lead)
                );

                $lead->update([
                    'admin_escalation_sent_at' => now(),
                ]);
            }
        }

    }

})->everyMinute();