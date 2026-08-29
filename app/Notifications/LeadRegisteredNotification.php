<?php

namespace App\Notifications;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeadRegisteredNotification extends Notification
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
            ->subject('Thank You for Your Enquiry')
            ->greeting('Hello ' . $this->lead->name . ',')
            ->line('Thank you for contacting us.')
            ->line(
                'We have received your enquiry regarding ' .
                $this->lead->product_service . '.'
            )
            ->line('One of our team members will contact you shortly.')
            ->line('If you have any additional questions, please feel free to contact us.')
            ->salutation('Kind regards,')
            ->salutation('Lead Management System');
    }
}