<?php

namespace App\Listeners;

use App\Events\ForgotPasswordEvent;
use App\Mail\ForgotPasswordMail;
use Illuminate\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;


class ForgotPasswordListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct(private Mailer $mailer)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ForgotPasswordEvent $event): void
    {
        $this -> mailer -> send(
            new ForgotPasswordMail($event -> mail, $event -> reset_code)
        );
    }
}
