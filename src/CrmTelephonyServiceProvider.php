<?php

namespace Enj0yer\CrmTelephony;

use Illuminate\Support\ServiceProvider;


class CrmTelephonyServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/telephony.php' => config_path('telephony.php'),
        ], 'config');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/telephony.php', 'telephony');
    }
}