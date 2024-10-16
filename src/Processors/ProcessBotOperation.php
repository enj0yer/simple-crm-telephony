<?php

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\TelephonyHandlerInputDataValidationException;
use Enj0yer\CrmTelephony\Helpers\UrlBuilder;
use Enj0yer\CrmTelephony\Processors\AbstractProcessor;
use Enj0yer\CrmTelephony\Response\TelephonyResponse;
use Enj0yer\CrmTelephony\Response\TelephonyResponseFactory;
use Illuminate\Support\Facades\Http;

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
        ]))->post(UrlBuilder::new($this->prefix, "/"));
        return TelephonyResponseFactory::createDefault($response);
    }

    public function getAll(): TelephonyResponse
    {
        $response = Http::get(UrlBuilder::new($this->prefix, "/"));
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

        $url = UrlBuilder::new($this->prefix, "/{bot_id}")
                         ->withUrlParameters(["bot_id" => $botId]);
        $response = Http::get($url);
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

        $url = UrlBuilder::new($this->prefix)
                         ->withQueryParameters(["botid" => $botId]);
        $response = Http::delete($url);
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
        
        $url = UrlBuilder::new($this->prefix, "/{bot_id}/dtmfnode/")
                         ->withUrlParameters(["bot_id" => $botId]);
        $response = Http::get($url);
        return TelephonyResponseFactory::createMultiple($response);
    }

}
