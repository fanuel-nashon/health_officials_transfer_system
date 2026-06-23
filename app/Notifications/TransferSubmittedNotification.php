<?php

namespace App\Notifications;

use App\Models\Transfers;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransferSubmittedNotification extends Notification
{
    use Queueable;

    public function __construct(public Transfers $transfer) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $applicant = $this->transfer->user->name;
        $from      = optional($this->transfer->fromFacility)->name ?? 'Unknown';
        $to        = optional($this->transfer->toFacility)->name   ?? 'Unknown';

        return (new MailMessage)
            ->subject('New Transfer Request Pending Your Review')
            ->greeting("Hello {$notifiable->name},")
            ->line("A new transfer request has been submitted by **{$applicant}** and is awaiting your review.")
            ->line("**From:** {$from}")
            ->line("**To:** {$to}")
            ->action('Review Transfer', url(route('transfers.review', $this->transfer)))
            ->line('Please log in to the Transfer Management System to review and take action.')
            ->salutation('Tanzania Ministry of Health — Transfer System');
    }

    public function toDatabase(object $notifiable): array
    {
        $applicant = $this->transfer->user->name;
        $from      = optional($this->transfer->fromFacility)->name ?? 'Unknown';

        return [
            'transfer_id' => $this->transfer->id,
            'title'       => 'New Transfer Request',
            'body'        => "{$applicant} has submitted a transfer request from {$from}.",
            'action_url'  => route('transfers.review', $this->transfer),
            'action_label'=> 'Review',
        ];
    }
}
