<?php

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\TelephonyHandlerInputDataValidationException;
use Enj0yer\CrmTelephony\Helpers\UrlBuilder;
use Enj0yer\CrmTelephony\Response\TelephonyResponseFactory;
use Enj0yer\CrmTelephony\Response\TelephonyResponse;
use Illuminate\Support\Facades\Http;

class ProcessCalls extends AbstractProcessor
{
    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function make(string $source, string $destination): TelephonyResponse
    {
        if (with([$source, $destination], fn ($args) => count(array_filter($args, fn ($value) => empty($value))) > 0))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        $response = Http::withBody(json_encode([
            'source' => $source,
            'destination' => $destination
        ]))->post(UrlBuilder::new($this->prefix, "/"));
        return TelephonyResponseFactory::createDefault($response);
    }
}