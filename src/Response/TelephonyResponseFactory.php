<?php

namespace Enj0yer\CrmTelephony\Response;

use Enj0yer\CrmTelephony\Response\TelephonyResponse;
use Illuminate\Http\Client\Response;

class TelephonyResponseFactory
{
    public static function createDefault(Response $response): TelephonyResponse
    {
        return new TelephonyResponse($response->status(), $response->json());
    }

    public static function createMultiple(Response $response): TelephonyResponse
    {
        return new TelephonyResponse($response->status(), $response->json(), true);
    }
}