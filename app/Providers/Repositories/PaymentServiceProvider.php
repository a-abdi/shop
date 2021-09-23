<?php

namespace App\Providers\Repositories;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Repositories\PaymentRepositoryInterface;
use App\Repositories\Eloquent\PaymentRepository;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
