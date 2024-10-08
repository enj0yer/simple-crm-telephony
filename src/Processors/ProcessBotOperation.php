<?php

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\TelephonyHandlerInputDataValidationException;
use Enj0yer\CrmTelephony\Processors\AbstractProcessor;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use function Enj0yer\CrmTelephony\Helpers\normalizeUrl;

class ProcessBotOperation extends AbstractProcessor
{
    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function create(string $name, string|null $inputDtmfStep = null): Response
    {
        if (with($name, fn($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        return Http::withBody(json_encode([
            "name" => $name,
            "input_dtmf_step" => $inputDtmfStep
        ]))->post(normalizeUrl($this->prefix, "/"));
    }

    public function getAll(): Response
    {
        return Http::get(normalizeUrl($this->prefix, "/"));
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function get(string $botId): Response
    {
        if (with($botId, fn($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        return Http::withUrlParameters([
            "bot_id" => $botId
        ])->get(normalizeUrl($this->prefix, "/{bot_id}"));

    }


    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function delete(string $botId): Response
    {
        if (with($botId, fn($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        return Http::withQueryParameters([
            "botid" => $botId
        ])->delete(normalizeUrl($this->prefix, "/"));

    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function getBotDtmfNodes(string $botId): Response
    {
        if (with($botId, fn($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        return Http::withUrlParameters([
            "bot_id" => $botId
        ])->get(normalizeUrl($this->prefix, "/{bot_id}/dtmfnode/"));

    }

}
