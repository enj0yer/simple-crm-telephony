<?php

namespace Enj0yer\CrmTelephony\Calls;

interface CallerEntity
{
    public function ableToMakeCalls(): bool;
}