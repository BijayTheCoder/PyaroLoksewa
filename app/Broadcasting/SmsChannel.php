<?php

namespace App\Broadcasting;

use App\Models\User;
use Illuminate\Notifications\Notification;

class SmsChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join(User $user): array|bool
    {
        //
    }

    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toSms($notifiable);

         // Now we hopefully have a instance of a SmsMessage.
        // That we are ready to send to our user.
        // Let's do it :-)
        // $message->send();

       // Or use dryRun() for testing to send it, without sending it for real.
        $message->dryRun()->send();
    }
}
