<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Services\MailService;
use App\Jobs\SendMailPasswordReset;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind('AuthService', AuthService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        $this->app->bindMethod([SendMailPasswordReset::class, 'handle'], function ($job, $app) {
            return $job->handle($app->make(MailService::class));
        });
    }
}
