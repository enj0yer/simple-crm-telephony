<?php

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\TelephonyHandlerInputDataValidationException;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use function Enj0yer\CrmTelephony\Helpers\normalizeUrl;

class ProcessDial extends AbstractProcessor
{
    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function dial(int $dialTaskId, string $callerId): Response
    {
        if (with([$dialTaskId, $callerId], fn ($params) => count(array_filter($params, fn ($value) => empty($value))) > 0))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        return Http::withQueryParameters([
            "dial_task_id" => $dialTaskId,
            "caller_id" => $callerId,
        ])->get(normalizeUrl($this->prefix, "/dial"));

    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function answer(int $dialTaskId, string $callerId): Response
    {
        if (with([$dialTaskId, $callerId], fn ($params) => count(array_filter($params, fn ($value) => empty($value))) > 0))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        return Http::withQueryParameters([
            "dial_task_id" => $dialTaskId,
            "caller_id" => $callerId,
        ])->get(normalizeUrl($this->prefix, "/answer"));
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function noAnswer(int $dialTaskId, string $callerId): Response
    {
        if (with([$dialTaskId, $callerId], fn ($params) => count(array_filter($params, fn ($value) => empty($value))) > 0))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        return Http::withQueryParameters([
            "dial_task_id" => $dialTaskId,
            "caller_id" => $callerId,
        ])->get(normalizeUrl($this->prefix, "/noanswer"));

    } 

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function status(int $dialTaskId, string $callerId, string $status): Response
    {
        if (with([$dialTaskId, $callerId, $status], fn ($params) => count(array_filter($params, fn ($value) => empty($value))) > 0))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        return Http::withQueryParameters([
            "dial_task_id" => $dialTaskId,
            "caller_id" => $callerId,
            "status" => $status,
        ])->get(normalizeUrl($this->prefix, "/status"));
    }
}