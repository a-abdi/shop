<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Services\MailService;
use App\Jobs\SendMailPasswordReset;
use App\Jobs\ClearTokenPasswordReset;
use  App\Repositories\Eloquent\PasswordResetRepository;
use App\Services\AuthService;
use App\Services\HttpService;

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

        $this->app->bindMethod([ClearTokenPasswordReset::class, 'handle'], function ($job, $app) {
            return $job->handle($app->make(PasswordResetRepository::class));
        });

        $this->app->bind(AuthService::class, function ($app) {
            return new AuthService($app->make(HttpService::class));
        });
    }
}
