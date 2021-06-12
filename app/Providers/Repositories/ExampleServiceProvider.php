<?php

namespace App\Providers\Repositories;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Repositories\ExampleRepositoryInterface;
use App\Repositories\Eloquent\ExampleRepository;

class ExampleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ExampleRepositoryInterface::class, ExampleRepository::class);
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
