<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\PasswordReset as MailPasswordReset;
use App\Services\MailService;

class SendMailPasswordReset implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private $email,
        private $link,
    )
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(MailService $mailService)
    {
        $mailService->sendMail(
            $this->email, 
            new MailPasswordReset($this->link)
        );
    }
}
