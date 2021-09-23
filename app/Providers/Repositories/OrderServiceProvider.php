<?php

namespace App\Providers\Repositories;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Repositories\Eloquent\OrderRepository;

class OrderServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
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
