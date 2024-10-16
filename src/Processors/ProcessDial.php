<?php

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\TelephonyHandlerInputDataValidationException;
use Enj0yer\CrmTelephony\Helpers\UrlBuilder;
use Enj0yer\CrmTelephony\Response\TelephonyResponse;
use Enj0yer\CrmTelephony\Response\TelephonyResponseFactory;
use Illuminate\Support\Facades\Http;

class ProcessDial extends AbstractProcessor
{
    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function dial(int $dialTaskId, string $callerId): TelephonyResponse
    {
        if (with([$dialTaskId, $callerId], fn ($params) => count(array_filter($params, fn ($value) => empty($value))) > 0))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        $url = UrlBuilder::new($this->prefix, "/dial")
                         ->withQueryParameters(["dial_task_id" => $dialTaskId,
                                                "caller_id" => $callerId]);
        $response = Http::get($url);
        return TelephonyResponseFactory::createDefault($response);
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function answer(int $dialTaskId, string $callerId): TelephonyResponse
    {
        if (with([$dialTaskId, $callerId], fn ($params) => count(array_filter($params, fn ($value) => empty($value))) > 0))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        $url = UrlBuilder::new($this->prefix, "/answer")
                         ->withQueryParameters(["dial_task_id" => $dialTaskId, 
                                                "caller_id" => $callerId]);
        $response = Http::get($url);
        return TelephonyResponseFactory::createDefault($response);
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function noAnswer(int $dialTaskId, string $callerId): TelephonyResponse
    {
        if (with([$dialTaskId, $callerId], fn ($params) => count(array_filter($params, fn ($value) => empty($value))) > 0))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        $url = UrlBuilder::new($this->prefix, "/noanswer")
                         ->withQueryParameters(["dial_task_id" => $dialTaskId, 
                                                "caller_id" => $callerId]);
        $response = Http::get($url);
        return TelephonyResponseFactory::createDefault($response);
    } 

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function status(int $dialTaskId, string $callerId, string $status): TelephonyResponse
    {
        if (with([$dialTaskId, $callerId, $status], fn ($params) => count(array_filter($params, fn ($value) => empty($value))) > 0))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        $url = UrlBuilder::new($this->prefix, "/status")
                         ->withQueryParameters(["dial_task_id" => $dialTaskId,
                                                "caller_id" => $callerId,
                                                "status" => $status]);
        $response = Http::get($url);
        return TelephonyResponseFactory::createDefault($response);
    }
}