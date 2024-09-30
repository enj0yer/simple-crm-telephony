<?php

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\InvalidTelephonyConfigValueException;
use Illuminate\Support\Facades\Config;
use ValueError;
use function Enj0yer\CrmTelephony\Helpers\normalizeUrl;

abstract class AbstractProcessor
{
    protected string $prefix;

    /**
     * @throws InvalidTelephonyConfigValueException
     */
    public function __construct(string $mainPrefix)
    {
        $serverIp = (!is_null(Config::getFacadeApplication())) ? Config::get('telephony.telephony_server_ip', '') : getenv("TELEPHONY_REMOTE_SERVER");
        if (empty($serverIp)) throw new InvalidTelephonyConfigValueException("Variable telephony_server_ip is not set in telephony.php");
        if (empty($mainPrefix)) throw new ValueError("Parameter 'mainPrefix' must not be empty");
        $this->prefix = normalizeUrl($serverIp, $mainPrefix);
        $this->prefix = normalizeUrl("", $mainPrefix);
    }
}