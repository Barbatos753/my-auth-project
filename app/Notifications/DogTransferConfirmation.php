<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DogTransferConfirmation extends Notification
{
    use Queueable;

    protected $dog;
    protected $newOwner;

    public function __construct($dog, $newOwner)
    {
        $this->dog = $dog;
        $this->newOwner = $newOwner;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Dog Transfer Confirmation')
            ->line("You have successfully transferred {$this->dog->name} to {$this->newOwner->name} ({$this->newOwner->email}).");
    }
}
