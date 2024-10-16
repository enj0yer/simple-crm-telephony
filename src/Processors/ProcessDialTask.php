<?php

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\TelephonyHandlerInputDataValidationException;
use Enj0yer\CrmTelephony\Helpers\UrlBuilder;
use Enj0yer\CrmTelephony\Processors\AbstractProcessor;
use Enj0yer\CrmTelephony\Response\TelephonyResponse;
use Enj0yer\CrmTelephony\Response\TelephonyResponseFactory;
use Illuminate\Support\Facades\Http;

class ProcessDialTask extends AbstractProcessor
{
    public function getAll(): TelephonyResponse
    {
        $response = Http::get(UrlBuilder::new($this->prefix, "/list"));
        return TelephonyResponseFactory::createMultiple($response);
    }

    public function get(int $dialTaskId): TelephonyResponse
    {
        if (with($dialTaskId, fn ($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }
        
        $url = UrlBuilder::new($this->prefix, "/{dialtask_id}")
                         ->withUrlParameters(["dialtask_id" => $dialTaskId]);
        $response = Http::get($url);
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
        ]))->post(UrlBuilder::new($this->prefix, "/"));
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

        $url = UrlBuilder::new($this->prefix, "/{dialtask_id}")
                         ->withUrlParameters(["dialtask_id" => $dialTaskId]);
        $response = Http::withBody(json_encode([
            "name" => $name,
            "schedule_id" => $scheduleId,
            "retry_logic_id" => $retryLogicId,
            "announcement_id" => $announcementId,
            "phone_group_id" => $phoneGroupId,
            "destination" => $destination,
            "destination_context" => $destinationContext,
        ]))->patch($url);
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

        $url = UrlBuilder::new($this->prefix, "/{dialtask_id}")
                         ->withUrlParameters(["dialtask_id" => $dialTaskId]);
        $response = Http::delete($url);
        return TelephonyResponseFactory::createDefault($response);
    }

    public function generate(): TelephonyResponse
    {
        $response = Http::post(UrlBuilder::new($this->prefix, "/generate"));
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

        $url = UrlBuilder::new($this->prefix, "/reset")
                         ->withQueryParameters(["dialtask_id" => $dialTaskId]);
        $response = Http::post($url);
        return TelephonyResponseFactory::createDefault($response);
    }
}