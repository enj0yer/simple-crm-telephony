<?php

namespace Enj0yer\CrmTelephony;

class TelephonyService
{
    public static function apiService(): TelephonyRawApiService
    {
        return new TelephonyRawApiService();
    }
}