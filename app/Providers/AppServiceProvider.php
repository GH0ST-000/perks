<?php

namespace App\Providers;

use App\Contracts\Sms\OtpProvider;
use App\Contracts\Sms\SmsSender;
use App\Services\GoSms\GoSmsOtpProvider;
use App\Services\GoSms\GoSmsSmsSender;
use App\Services\Sms\LocalOtpProvider;
use App\Services\Sms\LogSmsSender;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SmsSender::class, function () {
            return config('services.gosms.enabled')
                ? $this->app->make(GoSmsSmsSender::class)
                : $this->app->make(LogSmsSender::class);
        });

        $this->app->bind(OtpProvider::class, function () {
            return config('services.gosms.enabled')
                ? $this->app->make(GoSmsOtpProvider::class)
                : $this->app->make(LocalOtpProvider::class);
        });
    }

    public function boot(): void
    {
        //
    }
}
