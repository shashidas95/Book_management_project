<?php

namespace App\Infrastructure\Listeners;

use App\Infrastructure\Events\UserRegistered;
use App\Mail\WelcomeUserMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail implements ShouldQueue
{
    public function handle(UserRegistered $event)
    {
        echo "LOG: Sending Welcome Email to " . $event->user->email . "...\n";
        Mail::to($event->user->email)->send(new WelcomeUserMail($event->user));
    }
}
