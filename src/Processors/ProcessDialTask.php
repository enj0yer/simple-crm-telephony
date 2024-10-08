<?php

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\TelephonyHandlerInputDataValidationException;
use Enj0yer\CrmTelephony\Processors\AbstractProcessor;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use function Enj0yer\CrmTelephony\Helpers\normalizeUrl;

class ProcessDialTask extends AbstractProcessor
{
    public function getAll(): Response
    {
        return Http::get(normalizeUrl($this->prefix, "/list"));
    }

    public function get(int $dialTaskId): Response
    {
        if (with($dialTaskId, fn ($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        return Http::withUrlParameters([
            "dialtask_id" => $dialTaskId
        ])->get(normalizeUrl($this->prefix, "/{dialtask_id}"));
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function create(string $name,
                           int    $scheduleId,
                           int    $retryLogicId,
                           int    $announcementId,
                           int    $phoneGroupId,
                           string $destination,
                           string $destinationContext): Response
    {
        if (with([$name, $scheduleId, $retryLogicId, $announcementId, $phoneGroupId, $destination, $destinationContext],
            fn ($params) => count(array_filter($params, fn ($value) => empty($value))) > 0))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        return Http::withBody(json_encode([
            "name" => $name,
            "schedule_id" => $scheduleId,
            "retry_logic_id" => $retryLogicId,
            "announcement_id" => $announcementId,
            "phone_group_id" => $phoneGroupId,
            "destination" => $destination,
            "destination_context" => $destinationContext,
        ]))->post(normalizeUrl($this->prefix, "/"));
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function update(int    $dialTaskId,
                           string $name,
                           int    $scheduleId,
                           int    $retryLogicId,
                           int    $announcementId,
                           int    $phoneGroupId,
                           string $destination,
                           string $destinationContext): Response
    {
        if (with([$dialTaskId, $name, $scheduleId, $retryLogicId, $announcementId, $phoneGroupId, $destination, $destinationContext],
            fn ($params) => count(array_filter($params, fn ($value) => empty($value))) > 0))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        return Http::withUrlParameters([
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
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function delete(int $dialTaskId): Response
    {
        if (with($dialTaskId, fn ($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        return Http::withUrlParameters([
            "dialtask_id" => $dialTaskId
        ])->delete(normalizeUrl($this->prefix, "/{dialtask_id}"));
    }

    public function generate(): Response
    {
        return Http::post(normalizeUrl($this->prefix, "/generate"));
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function reset(int $dialTaskId): Response
    {
        if (with($dialTaskId, fn ($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        return Http::withQueryParameters([
            "dialtask_id" => $dialTaskId
        ])->post(normalizeUrl($this->prefix, "/reset"));
    }
}