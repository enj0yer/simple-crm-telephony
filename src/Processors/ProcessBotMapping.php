<?php

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\TelephonyHandlerInputDataValidationException;
use Enj0yer\CrmTelephony\Processors\AbstractProcessor;
use Enj0yer\CrmTelephony\Response\TelephonyResponse;
use Enj0yer\CrmTelephony\Response\TelephonyResponseFactory;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use function Enj0yer\CrmTelephony\Helpers\normalizeUrl;

class ProcessBotMapping extends AbstractProcessor
{
    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function create(int $botId, string $extensions): TelephonyResponse
    {
        if (with([$botId, $extensions], fn ($args) => count(array_filter($args, fn ($value) => empty($value))) > 0))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        $response = Http::withBody(json_encode([
            "bot_id" => $botId,
            "extensions" => $extensions
        ]))->post(normalizeUrl($this->prefix, "/"));
        return TelephonyResponseFactory::createDefault($response);
    }

    public function getAll(): TelephonyResponse
    {
        $response = Http::get(normalizeUrl($this->prefix, "/"));
        return TelephonyResponseFactory::createMultiple($response);
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function delete(string $botMappingId): TelephonyResponse
    {
        if (with($botMappingId, fn ($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        $response = Http::withQueryParameters([
            "bot_mapping_id" => $botMappingId
        ])->delete(normalizeUrl($this->prefix, "/"));
        return TelephonyResponseFactory::createDefault($response);
    }
}