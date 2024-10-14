<?php

namespace Enj0yer\CrmTelephony;
use BadMethodCallException;
use Enj0yer\CrmTelephony\Calls\CallableEntity;
use Enj0yer\CrmTelephony\Calls\CallerEntity;
use Illuminate\Support\Collection;

class BaseTelephonyService
{
    public static function apiService(): TelephonyRawApiService
    {
        return new TelephonyRawApiService();
    }

    public static function directCall(CallerEntity $caller, CallableEntity $callable)
    {
        throw new BadMethodCallException("Method is not implemented yet");    
    }
}
