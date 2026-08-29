<?php

namespace App\Notifications;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminFollowUpEscalation extends Notification
{
    use Queueable;

    public function __construct(
        public Lead $lead
    ) {
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Follow-up Escalation: ' . $this->lead->name)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A follow-up escalation requires your attention.')
            ->line('Lead: ' . $this->lead->name)
            ->line('Product / Service: ' . $this->lead->product_service)
            ->line('Status: ' . $this->lead->status)
            ->line(
                'Assigned Staff: ' .
                ($this->lead->assignedStaff->name ?? 'Unassigned')
            )
            ->line(
                'Follow-up Reminder Sent: ' .
                ($this->lead->follow_up_reminder_sent_at
                    ? $this->lead->follow_up_reminder_sent_at->format('d M Y, h:i A')
                    : 'Not recorded')
            )
            ->line(
                'The assigned staff member has not recorded any activity for this lead after the follow-up reminder.'
            )
            ->action(
                'View Lead',
                route('leads.show', $this->lead)
            )
            ->line(
                'Please review this lead and take the necessary action.'
            );
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'lead_id' => $this->lead->id,
            'lead_name' => $this->lead->name,
            'assigned_staff' => $this->lead->assignedStaff->name ?? null,
        ];
    }


    /**
     * Database notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'follow_up_escalation',
            'title' => 'Follow-up Escalation',
            'message' => 'Follow-up for ' . $this->lead->name . ' requires your attention.',
            'lead_id' => $this->lead->id,
            'url' => route('leads.show', $this->lead),
        ];
    }


}