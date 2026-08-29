<?php

namespace App\Console\Commands;

use App\Models\Lead;
use App\Models\User;
use App\Notifications\AdminFollowUpEscalation;
use App\Notifications\FollowUpReminder;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:check-lead-follow-ups')]
#[Description('Check leads for staff follow-up reminders and admin escalations')]
class CheckLeadFollowUps extends Command
{
    public function handle()
    {
        $this->info('Checking lead follow-ups...');

        $leads = Lead::with([
            'assignedStaff',
            'activities' => function ($query) {
                $query->latest('created_at');
            },
        ])
            ->whereNotNull('assigned_to')
            ->whereNotIn('status', ['Converted', 'Lost'])
            ->get();

        foreach ($leads as $lead) {

            /*
            |--------------------------------------------------------------------------
            | Make sure the lead has an assigned staff member
            |--------------------------------------------------------------------------
            */

            if (!$lead->assignedStaff) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Get the latest activity
            |--------------------------------------------------------------------------
            */

            $latestActivity = $lead->activities->first();

            /*
            |--------------------------------------------------------------------------
            | If there is no activity yet, do nothing
            |--------------------------------------------------------------------------
            |
            | The initial staff assignment email is handled separately.
            | The 3-day inactivity timer starts once staff records an activity.
            |
            */

            if (!$latestActivity) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | STEP 1
            | Staff has been inactive for 3 days
            |--------------------------------------------------------------------------
            */

            if (!$lead->follow_up_reminder_sent_at) {

                if ($latestActivity->created_at->lte(now()->subDays(3))) {

                    $lead->assignedStaff->notify(
                        new FollowUpReminder($lead)
                    );

                    $lead->update([
                        'follow_up_reminder_sent_at' => now(),
                    ]);

                    $this->info(
                        "Reminder sent to {$lead->assignedStaff->name} for lead: {$lead->name}"
                    );
                }

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | STEP 2
            | Reminder was sent.
            | Give staff 1 more day.
            |--------------------------------------------------------------------------
            */

            if (
                $lead->follow_up_reminder_sent_at->lte(now()->subDay())
            ) {

                /*
                |--------------------------------------------------------------------------
                | Check whether staff recorded an activity AFTER the reminder
                |--------------------------------------------------------------------------
                */

                $activityAfterReminder = $lead->activities->first(
                    function ($activity) use ($lead) {
                        return $activity->created_at->gt(
                            $lead->follow_up_reminder_sent_at
                        );
                    }
                );

                /*
                |--------------------------------------------------------------------------
                | Staff followed up
                | Reset the reminder cycle
                |--------------------------------------------------------------------------
                */

                if ($activityAfterReminder) {

                    $lead->update([
                        'follow_up_reminder_sent_at' => null,
                        'admin_escalation_sent_at' => null,
                    ]);

                    $this->info(
                        "Follow-up activity recorded for lead: {$lead->name}. Reminder cycle reset."
                    );

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | STEP 3
                | No activity after reminder.
                | Notify all admins.
                |--------------------------------------------------------------------------
                */

                if (!$lead->admin_escalation_sent_at) {

                    $admins = User::where('role', 'admin')->get();

                    foreach ($admins as $admin) {

                        $admin->notify(
                            new AdminFollowUpEscalation($lead)
                        );
                    }

                    $lead->update([
                        'admin_escalation_sent_at' => now(),
                    ]);

                    $this->warn(
                        "Admin escalation sent for lead: {$lead->name}"
                    );
                }
            }
        }

        $this->info('Lead follow-up check completed.');

        return Command::SUCCESS;
    }
}