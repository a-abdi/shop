<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Mail;

class MailService
{
     /**
     * Send email.
     *
     * @param  string $mail 
     * @param  App\Mail 
     * @return void
     */
    public function sendMail($email, $mail)
    {
        Mail::to($email)->send($mail);
    }
}