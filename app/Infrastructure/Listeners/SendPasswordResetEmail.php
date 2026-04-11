<?php

namespace App\Infrastructure\Listeners;

use App\Infrastructure\Events\PasswordResetRequested;
use App\Mail\ResetPasswordMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendPasswordResetEmail implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PasswordResetRequested $event): void
    {
        Log::info('Listener triggered for: ' . $event->email);

     Mail::to($event->email)->send(new ResetPasswordMail($event->token, $event->email));
    }
}
