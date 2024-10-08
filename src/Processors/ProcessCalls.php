<?php

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\TelephonyHandlerInputDataValidationException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use function Enj0yer\CrmTelephony\Helpers\normalizeUrl;

class ProcessCalls extends AbstractProcessor
{
    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function make(string $source, string $destination): Response
    {
        if (with([$source, $destination], fn ($args) => count(array_filter($args, fn ($value) => empty($value))) > 0))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        return Http::withBody(json_encode([
            'source' => $source,
            'destination' => $destination
        ]))->post(normalizeUrl($this->prefix, "/"));

        // TODO: Change to more suitable return type

    }
}