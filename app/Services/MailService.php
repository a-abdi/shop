<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Mail\Mailable;

class MailService
{
     /**
     * Send email.
     *
     * @param  string $mail 
     * @param  App\Mail 
     * @return void
     */
    public function sendMail($email, Mailable $mail)
    {
        Mail::to($email)->send($mail);
    }
}