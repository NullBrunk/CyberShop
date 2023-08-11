<?php

namespace App\Listeners;

use App\Events\SignupEvent;
use App\Mail\ConfirmMail;
use Illuminate\Mail\Mailer;


class SignupListenner
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
    public function handle(SignupEvent $event): void
    {
        $this -> mailer -> send(
            new ConfirmMail($event -> mail, $event -> checksum)
        );
    }
}
