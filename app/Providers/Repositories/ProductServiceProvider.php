<?php

namespace App\Providers\Repositories;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Repositories\Eloquent\ProductRepository;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
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
