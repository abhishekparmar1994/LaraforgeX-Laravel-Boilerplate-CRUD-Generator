<?php

declare(strict_types=1);

namespace App\Domains\EquipmentSupplie\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EquipmentSupplieNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('EquipmentSupplie Alert')
            ->line('Your EquipmentSupplie record has been processed.')
            ->action('View Records', url('/'));
    }
}