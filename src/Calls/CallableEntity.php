<?php

namespace Enj0yer\CrmTelephony\Calls;

interface CallableEntity
{
    public function call(CallerEntity $caller = null);
}