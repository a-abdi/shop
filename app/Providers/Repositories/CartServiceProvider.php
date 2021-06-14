<?php

namespace App\Providers\Repositories;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Repositories\CartRepositoryInterface;
use App\Repositories\Eloquent\CartRepository;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CartRepositoryInterface::class, CartRepository::class);
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
