<?php

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\TelephonyHandlerInputDataValidationException;
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
    public function create(string $name): Response
    {
        if (!isAllNonEmpty($name))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `name` must be non empty");
        }
        
        return Http::withBody(json_encode([
            'name' => $name
            ]))->post(normalizeUrl($this->prefix, '/'));
            
    }
        
    public function update(int $scheduleId, string $name): Response
    {
        if (!isAllPozitive($scheduleId))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `scheduleId` must be greater than zero, provided $scheduleId");
        }
        if (!isAllNonEmpty($name))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `name` must be non empty");
        }

        return Http::withBody(json_encode([
            'schedule_id' => $scheduleId,
            'name' => $name
        ]))->patch(normalizeUrl($this->prefix, '/'));
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function delete(string $scheduleId): Response
    {
        if (!isAllPozitive($scheduleId))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `scheduleId` must be greater than zero, provided $scheduleId");
        }

        return Http::withUrlParameters([
            'schedule_id' => $scheduleId
        ])->delete(normalizeUrl($this->prefix, "/{schedule_id}"));
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function get(int $scheduleId): Response
    {
        if (!isAllPozitive($scheduleId))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `scheduleId` must be greater than zero, provided $scheduleId");
        }

        return Http::withUrlParameters([
            'schedule_id' => $scheduleId
        ])->get(normalizeUrl($this->prefix, "/{schedule_id}"));
    }

    public function getAll(): Response
    {
        return Http::get(normalizeUrl($this->prefix, "/list"));
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function setParameters(int $scheduleId, array $params): Response
    {
        if (!isAllPozitive($scheduleId))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `scheduleId` must be greater than zero, provided $scheduleId");
        }
        
        if (!isArrayWithOnlyNonEmptyKeysAndValues($params))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `params` must be an array, which contains only non empty keys and values");
        }

        return Http::withUrlParameters([
            'schedule_id' => $scheduleId
        ])->withBody(json_encode(
            array_map(fn ($key) => ['key' => $key, 'value' => $params[$key]], array_keys($params))
        ))->post(normalizeUrl($this->prefix, "/parameters", "/{schedule_id}"));
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function deleteParameter(int $scheduleParametersId): Response
    {
        if (!isAllPozitive($scheduleParametersId))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `scheduleId` must be greater than zero, provided $scheduleParametersId");
        }
        return Http::withUrlParameters([
            'schedule_parameters_id' => $scheduleParametersId
        ])->delete(normalizeUrl($this->prefix, "/parameters", "/{schedule_parameters_id}"));
    }
}