<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DogReceivedConfirmation extends Notification
{
    use Queueable;

    protected $dog;
    protected $currentUser;

    public function __construct($dog, $currentUser)
    {
        $this->dog = $dog;
        $this->currentUser = $currentUser;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Dog Received Confirmation')
            ->line("You have successfully received {$this->dog->name} from {$this->currentUser->name} ({$this->currentUser->email}).");
    }
}
