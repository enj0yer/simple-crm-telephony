<?php

namespace Enj0yer\CrmTelephony\Helpers;

function isEntityContainsProperty($entity, $propertyName): bool
{
    return !empty($entity->{$propertyName});
}

function assocArrayByKey(array $array, $keyToExtract = 'id'): array
{
    $new = [];
    foreach ($array as $item) {
        if (array_key_exists($keyToExtract, $item)) {
            $new[$item[$keyToExtract]] = $item;
        }
    }
    return $new;
}