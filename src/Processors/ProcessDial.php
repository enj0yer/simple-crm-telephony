<?php

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\TelephonyHandlerInputDataValidationException;
use Enj0yer\CrmTelephony\Response\TelephonyResponse;
use Enj0yer\CrmTelephony\Response\TelephonyResponseFactory;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use function Enj0yer\CrmTelephony\Helpers\normalizeUrl;

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

        $response = Http::withQueryParameters([
            "dial_task_id" => $dialTaskId,
            "caller_id" => $callerId,
        ])->get(normalizeUrl($this->prefix, "/dial"));
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

        $response = Http::withQueryParameters([
            "dial_task_id" => $dialTaskId,
            "caller_id" => $callerId,
        ])->get(normalizeUrl($this->prefix, "/answer"));
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

        $response = Http::withQueryParameters([
            "dial_task_id" => $dialTaskId,
            "caller_id" => $callerId,
        ])->get(normalizeUrl($this->prefix, "/noanswer"));
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

        $response = Http::withQueryParameters([
            "dial_task_id" => $dialTaskId,
            "caller_id" => $callerId,
            "status" => $status,
        ])->get(normalizeUrl($this->prefix, "/status"));
        return TelephonyResponseFactory::createDefault($response);
    }
}