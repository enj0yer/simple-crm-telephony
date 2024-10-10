<?php

namespace Enj0yer\CrmTelephony\Calls;
use Enj0yer\CrmTelephony\Exceptions\InvalidTelephonyCallableEntityException;
use Enj0yer\CrmTelephony\TelephonyService;
use Illuminate\Support\Facades\Auth;

trait CanBeCalled
{
    public function call(CallerEntity $caller = null)
    {
        if (!$this instanceof CallableEntity || !isset($this->phone) || !is_string($this->phone))
        {
            return new InvalidTelephonyCallableEntityException("Callable entity must implements `CallableEntity` interface and contains `phone` property");
        }
        TelephonyService::directCall($caller, $this);
    }
}