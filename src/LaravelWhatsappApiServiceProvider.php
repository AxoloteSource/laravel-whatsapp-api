<?php

namespace Axolotesource\LaravelWhatsappApi;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;

class LaravelWhatsappApiServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-whatsapp-api.php', 'laravel-whatsapp-api');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/laravel-whatsapp-api.php' => config_path('laravel-whatsapp-api.php'),
        ], 'config');
        AboutCommand::add('Laravel Whatsapp Api', fn () => ['Version' => '1.0']);
    }
}
