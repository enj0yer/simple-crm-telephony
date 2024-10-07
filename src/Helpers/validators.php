<?php

namespace Enj0yer\CrmTelephony\Helpers;

function isAllNonEmpty(...$values): bool
{
    return !with($values, fn ($vs) => count(array_filter($vs, fn ($v) => empty($v))) > 0);
}

function isAllPozitive(...$values): bool
{
    return !with($values, fn ($vs) => count(array_filter($vs, fn ($v) => $v <= 0)) > 0);
}

function isAssocArrayWithStringNonEmptyKeys(array $array): bool
{
    return !with($array, fn ($a) => count(array_filter(array_keys($a), fn ($key) => !is_string($key) || empty($key))) > 0);
}
