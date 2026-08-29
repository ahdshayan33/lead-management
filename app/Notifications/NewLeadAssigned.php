<?php

namespace App\Notifications;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewLeadAssigned extends Notification
{
    use Queueable;

    public function __construct(
        public Lead $lead
    ) {
    }

    /**
     * Get the notification delivery channels.
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
            ->subject('New Lead Assigned: ' . $this->lead->name)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A new lead has been assigned to you.')
            ->line('Lead: ' . $this->lead->name)
            ->line('Product / Service: ' . $this->lead->product_service)
            ->line('Phone: ' . $this->lead->phone)
            ->line('Email: ' . ($this->lead->email ?? 'Not provided'))
            ->line('Please contact this lead within 24 hours.')
            ->action(
                'View Lead',
                route('leads.show', $this->lead)
            )
            ->line('Please record the contact activity in the Lead Management System after contacting the lead.');
    }

    /**
     * Database notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'new_lead',
            'title' => 'New Lead Assigned',
            'message' => $this->lead->name . ' has been assigned to you.',
            'lead_id' => $this->lead->id,
            'url' => route('leads.show', $this->lead),
        ];
    }







}