<?php

namespace App\Notifications;

use App\Models\Transfers;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransferStatusUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Transfers $transfer,
        public string    $action,   // 'approved' | 'rejected' | 'forwarded'
        public string    $level,    // 'facility' | 'district' | 'region' | 'ministry'
        public ?string   $comment = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $from    = optional($this->transfer->fromFacility)->name ?? 'Unknown';
        $to      = optional($this->transfer->toFacility)->name   ?? 'Unknown';
        $levelLabel = ucfirst($this->level);

        $subject = match ($this->action) {
            'approved'  => "Your Transfer Request Has Been Finally Approved",
            'rejected'  => "Your Transfer Request Was Rejected at {$levelLabel} Level",
            default     => "Your Transfer Request Progressed to Next Level",
        };

        $statusLine = match ($this->action) {
            'approved'  => "Congratulations! Your transfer request has been **finally approved** by the Ministry.",
            'rejected'  => "Your transfer request was **rejected** at the **{$levelLabel}** level.",
            default     => "Your transfer request has been reviewed at the **{$levelLabel}** level and forwarded to the next reviewer.",
        };

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting("Hello {$notifiable->name},")
            ->line($statusLine)
            ->line("**From:** {$from}")
            ->line("**To:** {$to}");

        if ($this->comment) {
            $mail->line("**Reviewer comment:** {$this->comment}");
        }

        return $mail
            ->action('View Transfer Details', url(route('transfers.show', $this->transfer)))
            ->salutation('Tanzania Ministry of Health — Transfer System');
    }

    public function toDatabase(object $notifiable): array
    {
        $levelLabel = ucfirst($this->level);

        $title = match ($this->action) {
            'approved' => 'Transfer Finally Approved',
            'rejected' => "Transfer Rejected at {$levelLabel} Level",
            default    => "Transfer Forwarded — {$levelLabel} Level Approved",
        };

        $body = match ($this->action) {
            'approved' => 'Your transfer request has been approved by the Ministry.',
            'rejected' => "Your transfer was rejected at the {$levelLabel} level." . ($this->comment ? " Comment: {$this->comment}" : ''),
            default    => "Your transfer passed the {$levelLabel} review and is now at the next level.",
        };

        return [
            'transfer_id'  => $this->transfer->id,
            'title'        => $title,
            'body'         => $body,
            'action_url'   => route('transfers.show', $this->transfer),
            'action_label' => 'View',
        ];
    }
}
