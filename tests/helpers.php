<?php

namespace Enj0yer\CrmTelephony\Tests;

use GuzzleHttp\Psr7\HttpFactory;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use GuzzleHttp\Client;
use Illuminate\Config;

function bootstrapFacades()
{
    $app = new Container();
    Facade::setFacadeApplication($app);

    $app->singleton('http', function () {
        return new \Illuminate\Http\Client\Factory();
    });
}