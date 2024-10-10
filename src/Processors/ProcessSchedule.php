<?php

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\TelephonyHandlerInputDataValidationException;
use Enj0yer\CrmTelephony\Response\TelephonyResponse;
use Enj0yer\CrmTelephony\Response\TelephonyResponseFactory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use function Enj0yer\CrmTelephony\Helpers\isAllNonEmpty;
use function Enj0yer\CrmTelephony\Helpers\isAllPozitive;
use function Enj0yer\CrmTelephony\Helpers\isArrayWithOnlyNonEmptyKeysAndValues;
use function Enj0yer\CrmTelephony\Helpers\normalizeUrl;

class ProcessSchedule extends AbstractProcessor
{
    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function create(string $name): TelephonyResponse
    {
        if (!isAllNonEmpty($name))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `name` must be non empty");
        }
        
        $response = Http::withBody(json_encode([
            'name' => $name
            ]))->post(normalizeUrl($this->prefix, '/'));
        return TelephonyResponseFactory::createDefault($response);
    }
        
    public function update(int $scheduleId, string $name): TelephonyResponse
    {
        if (!isAllPozitive($scheduleId))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `scheduleId` must be greater than zero, provided $scheduleId");
        }
        if (!isAllNonEmpty($name))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `name` must be non empty");
        }

        $response = Http::withBody(json_encode([
            'schedule_id' => $scheduleId,
            'name' => $name
        ]))->patch(normalizeUrl($this->prefix, '/'));
        return TelephonyResponseFactory::createDefault($response);
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function delete(string $scheduleId): TelephonyResponse
    {
        if (!isAllPozitive($scheduleId))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `scheduleId` must be greater than zero, provided $scheduleId");
        }

        $response = Http::withUrlParameters([
            'schedule_id' => $scheduleId
        ])->delete(normalizeUrl($this->prefix, "/{schedule_id}"));
        return TelephonyResponseFactory::createDefault($response);
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function get(int $scheduleId): TelephonyResponse
    {
        if (!isAllPozitive($scheduleId))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `scheduleId` must be greater than zero, provided $scheduleId");
        }
        $response = Http::withUrlParameters([
            'schedule_id' => $scheduleId
        ])->get(normalizeUrl($this->prefix, "/{schedule_id}"));
        return TelephonyResponseFactory::createDefault($response);
    }

    public function getAll(): TelephonyResponse
    {
        $response = Http::get(normalizeUrl($this->prefix, "/list"));
        return TelephonyResponseFactory::createMultiple($response);
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function setParameters(int $scheduleId, array $params): TelephonyResponse
    {
        if (!isAllPozitive($scheduleId))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `scheduleId` must be greater than zero, provided $scheduleId");
        }
        
        if (!isArrayWithOnlyNonEmptyKeysAndValues($params))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `params` must be an array, which contains only non empty keys and values");
        }

        $response = Http::withUrlParameters([
            'schedule_id' => $scheduleId
        ])->withBody(json_encode(
            array_map(fn ($key) => ['key' => $key, 'value' => $params[$key]], array_keys($params))
        ))->post(normalizeUrl($this->prefix, "/parameters", "/{schedule_id}"));
        return TelephonyResponseFactory::createDefault($response);
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function deleteParameter(int $scheduleParametersId): TelephonyResponse
    {
        if (!isAllPozitive($scheduleParametersId))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `scheduleId` must be greater than zero, provided $scheduleParametersId");
        }
        $response = Http::withUrlParameters([
            'schedule_parameters_id' => $scheduleParametersId
        ])->delete(normalizeUrl($this->prefix, "/parameters", "/{schedule_parameters_id}"));
        return TelephonyResponseFactory::createDefault($response);
    }
}