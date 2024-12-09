<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Xendit\Xendit;

class XenditServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Xendit::class, function () {
            Xendit::setApiKey(config('services.xendit.secret_key'));
            return new Xendit();
        });
    }

    public function boot()
    {
        //
    }
}
