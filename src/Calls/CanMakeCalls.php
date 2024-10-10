<?php

namespace Enj0yer\CrmTelephony\Calls;

trait CanMakeCalls
{
    public function ableToMakeCall(): bool
    {
        return $this instanceof CallerEntity;
    }
}