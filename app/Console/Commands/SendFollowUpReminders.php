<?php

namespace App\Console\Commands;

use App\Models\Lead;
use App\Notifications\FollowUpReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendFollowUpReminders extends Command
{
    protected $signature = 'leads:send-follow-up-reminders';

    protected $description = 'Send email reminders for overdue and upcoming lead follow-ups';

    public function handle(): int
    {
        $today = Carbon::today();

        $leads = Lead::with('assignedStaff')
            ->whereNotNull('follow_up_date')
            ->whereNotNull('assigned_to')
            ->whereDate('follow_up_date', '<=', $today)
            ->get();

        if ($leads->isEmpty()) {
            $this->info('No leads require follow-up reminders.');
            return self::SUCCESS;
        }

        foreach ($leads as $lead) {

            if (!$lead->assignedStaff) {
                continue;
            }

            $lead->assignedStaff->notify(
                new FollowUpReminder($lead)
            );

            if (Carbon::parse($lead->follow_up_date)->isPast()) {
                $this->info(
                    "Overdue reminder sent for: {$lead->name}"
                );
            } else {
                $this->info(
                    "Today's reminder sent for: {$lead->name}"
                );
            }
        }

        $this->info('Follow-up reminder process completed.');

        return self::SUCCESS;
    }
}