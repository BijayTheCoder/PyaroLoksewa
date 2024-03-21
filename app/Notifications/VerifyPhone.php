<?php

namespace App\Notifications;

use App\Broadcasting\SmsChannel;
use App\Http\Services\UserOtp;
use App\Jobs\SMSMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyPhone extends Notification
{
    use Queueable;
    protected $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function via($notifiable)
    {
        // So instead of the "de fault" mail channel.. Lets change our notification to notify user with the SmsChannel class.
        // We also need to import our SmsChannel at the top of our file for this to work.
        return [SmsChannel::class];
        // return ['mail'];
    }

    public function toSms($notifiable)
    {
        // We are assuming we are notifying a user or a model that has a telephone attribute/field. 
        // And the telephone number is correctly formatted.
        return (new SMSMessage())
                    ->from('PYARO LOKSEWA')
                    ->to($notifiable->identifier)
                    ->line($this->code." is your verification code for PYARO LOKSEWA");
    }
}