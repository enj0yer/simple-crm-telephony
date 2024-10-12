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

class ProcessBotOperation extends AbstractProcessor
{
    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function create(string $name, string|null $inputDtmfStep = null): TelephonyResponse
    {
        if (with($name, fn($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        $response = Http::withBody(json_encode([
            "name" => $name,
            "input_dtmf_step" => $inputDtmfStep
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
    public function get(string $botId): TelephonyResponse
    {
        if (with($botId, fn($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        $response = Http::withUrlParameters([
            "bot_id" => $botId
        ])->get(normalizeUrl($this->prefix, "/{bot_id}"));
        return TelephonyResponseFactory::createDefault($response);
    }


    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function delete(string $botId): TelephonyResponse
    {
        if (with($botId, fn($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        $response = Http::withQueryParameters([
            "botid" => $botId
        ])->delete(normalizeUrl($this->prefix, "/"));
        return TelephonyResponseFactory::createDefault($response);
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function getBotDtmfNodes(string $botId): TelephonyResponse
    {
        if (with($botId, fn($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        $response = Http::withUrlParameters([
            "bot_id" => $botId
        ])->get(normalizeUrl($this->prefix, "/{bot_id}/dtmfnode/"));
        return TelephonyResponseFactory::createMultiple($response);
    }

}
