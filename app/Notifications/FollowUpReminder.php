<?php

namespace App\Notifications;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FollowUpReminder extends Notification
{
    use Queueable;

    public function __construct(
        public Lead $lead
    ) {
    }

    /**
     * Notification delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Email notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Lead Follow-up Reminder: ' . $this->lead->name)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('This is a reminder that a lead requires your follow-up.')
            ->line('Lead: ' . $this->lead->name)
            ->line('Product / Service: ' . $this->lead->product_service)
            ->line('Status: ' . $this->lead->status)
            ->line(
                'Follow-up Date: ' .
                ($this->lead->follow_up_date
                    ? \Carbon\Carbon::parse($this->lead->follow_up_date)->format('d M Y')
                    : 'Not scheduled')
            )
            ->action(
                'View Lead',
                route('leads.show', $this->lead)
            )
            ->line('Please follow up with this lead as soon as possible.');
    }
}