<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserRejected extends Notification
{
    use Queueable;

    protected $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct($reason = null)
    {
        $this->reason = $reason;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
                    ->subject('Conta rejeitada - ' . config('app.name'))
                    ->greeting('Olá ' . $notifiable->name . '!')
                    ->line('Infelizmente, sua solicitação de conta foi rejeitada pelo administrador.');
        
        if ($this->reason) {
            $message->line('Motivo: ' . $this->reason);
        }
        
        $message->line('Se você acredita que isso foi um erro, entre em contato conosco.')
                ->line('Obrigado pelo seu interesse em nosso sistema.');
        
        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Sua solicitação de conta foi rejeitada.',
            'reason' => $this->reason,
        ];
    }
}