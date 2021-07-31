<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Repositories\Eloquent\PasswordResetRepository;

class ClearTokenPasswordReset implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private $email)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(PasswordResetRepository $passwordResetRepository)
    {
        $passwordResetRepository->deleteToken($this->email);
    }
}
