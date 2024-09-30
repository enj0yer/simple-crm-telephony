<?php

namespace Enj0yer\CrmTelephony\Helpers;

function isEntityContainsProperty($entity, $propertyName): bool
{
    return !empty($entity->{$propertyName});
}

function normalizeUrl(string ...$url): string
{
    $regex = "#(?<!https|http:)/{2,}#";
    return preg_replace($regex, "/", implode("/", $url));
}