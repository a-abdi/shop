<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Support\Facades\Log;

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
        Log::debug(config('mail.mailers.smtp.password'));
        Mail::to($email)->send($mail);
    }
}