<?php

namespace Enj0yer\CrmTelephony\Helpers;

function isEntityContainsProperty($entity, $propertyName): bool
{
    return !empty($entity->{$propertyName});
}
