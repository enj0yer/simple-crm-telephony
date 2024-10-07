<?php

namespace Enj0yer\CrmTelephony\Tests;

use Illuminate\Container\Container;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Facade;

function bootstrapFacades()
{
    $app = new Container();
    Facade::setFacadeApplication($app);

    $app->singleton('http', function () {
        return new \Illuminate\Http\Client\Factory();
    });
}

function randomString(int $length): string
{
    $symbols = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    $numbers = "0123456789";
    $allSymbols = $symbols . $numbers;
    $symbolsLength = strlen($allSymbols);

    $result = "";
    for ($i = 0; $i < $length; $i++) {
        if ($i == 0) {
            $result .= $symbols[random_int(0, strlen($symbols) - 1)];
        }
        $result .= $allSymbols[random_int(0, $symbolsLength - 1)];
    }

    return $result;
}

function randomPassword(int $length = 32): string 
{
    $symbols = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!'#&%*+,-./:;<=>?@[]^_{}|~()";
    $symbolsLength = strlen($symbols);
    $result = "";
    for ($i = 0; $i < $length; $i++) { 
        $result .= $symbols[random_int(0, $symbolsLength - 1)];
    }

    return $result;
}

function dumpHttpResponse(Response $response)  
{   
    print "\nResponse:";
    print "\n\t" . "Status code: " . $response->status();
    print "\n\t" . "Headers: " . json_encode($response->headers(), JSON_UNESCAPED_UNICODE);
    print "\n\t" . "Data: " . json_encode($response->json(), JSON_UNESCAPED_UNICODE);
    print "\n";
}

function dumpValues(...$vars)
{
    print "\nDumping values: ";
    foreach ($vars as $varName => $value)
    {
        print "\n\t$varName => $value";
    }
    print "\n";
}