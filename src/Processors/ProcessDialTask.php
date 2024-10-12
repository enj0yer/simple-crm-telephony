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

class ProcessDialTask extends AbstractProcessor
{
    public function getAll(): TelephonyResponse
    {
        $response = Http::get(normalizeUrl($this->prefix, "/list"));
        return TelephonyResponseFactory::createMultiple($response);
    }

    public function get(int $dialTaskId): TelephonyResponse
    {
        if (with($dialTaskId, fn ($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        $response = Http::withUrlParameters([
            "dialtask_id" => $dialTaskId
        ])->get(normalizeUrl($this->prefix, "/{dialtask_id}"));
        return TelephonyResponseFactory::createDefault($response);
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function create(string $name,                // TODO: Optimize amount of parameters
                           int    $scheduleId,
                           int    $retryLogicId,
                           int    $announcementId,
                           int    $phoneGroupId,
                           string $destination,
                           string $destinationContext): TelephonyResponse
    {
        if (with([$name, $scheduleId, $retryLogicId, $announcementId, $phoneGroupId, $destination, $destinationContext],
            fn ($params) => count(array_filter($params, fn ($value) => empty($value))) > 0))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        $response = Http::withBody(json_encode([
            "name" => $name,
            "schedule_id" => $scheduleId,
            "retry_logic_id" => $retryLogicId,
            "announcement_id" => $announcementId,
            "phone_group_id" => $phoneGroupId,
            "destination" => $destination,
            "destination_context" => $destinationContext,
        ]))->post(normalizeUrl($this->prefix, "/"));
        return TelephonyResponseFactory::createDefault($response);
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function update(int    $dialTaskId,           // TODO: Optimize amount of parameters
                           string $name,
                           int    $scheduleId,
                           int    $retryLogicId,
                           int    $announcementId,
                           int    $phoneGroupId,
                           string $destination,
                           string $destinationContext): TelephonyResponse
    {
        if (with([$dialTaskId, $name, $scheduleId, $retryLogicId, $announcementId, $phoneGroupId, $destination, $destinationContext],
            fn ($params) => count(array_filter($params, fn ($value) => empty($value))) > 0))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        $response = Http::withUrlParameters([
            "dialtask_id" => $dialTaskId
        ])
        ->withBody(json_encode([
            "name" => $name,
            "schedule_id" => $scheduleId,
            "retry_logic_id" => $retryLogicId,
            "announcement_id" => $announcementId,
            "phone_group_id" => $phoneGroupId,
            "destination" => $destination,
            "destination_context" => $destinationContext,
        ]))->patch(normalizeUrl($this->prefix, "/{dialtask_id}"));
        return TelephonyResponseFactory::createDefault($response);
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function delete(int $dialTaskId): TelephonyResponse
    {
        if (with($dialTaskId, fn ($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        $response = Http::withUrlParameters([
            "dialtask_id" => $dialTaskId
        ])->delete(normalizeUrl($this->prefix, "/{dialtask_id}"));
        return TelephonyResponseFactory::createDefault($response);
    }

    public function generate(): TelephonyResponse
    {
        $response = Http::post(normalizeUrl($this->prefix, "/generate"));
        return TelephonyResponseFactory::createDefault($response);
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function reset(int $dialTaskId): TelephonyResponse
    {
        if (with($dialTaskId, fn ($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        $response = Http::withQueryParameters([
            "dialtask_id" => $dialTaskId
        ])->post(normalizeUrl($this->prefix, "/reset"));
        return TelephonyResponseFactory::createDefault($response);
    }
}