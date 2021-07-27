<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\PasswordReset as MailPasswordReset;

class PasswordReset implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $authService;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private $passwordReset,
    )
    {
        $this->authService = resolve('AuthService');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->authService->sendMail(
            $this->passwordReset->email, 
            new MailPasswordReset($this->passwordReset->link)
        );
    }
}
