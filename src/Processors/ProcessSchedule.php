<?php

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\TelephonyHandlerInputDataValidationException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use function Enj0yer\CrmTelephony\Helpers\normalizeUrl;

class ProcessSchedule extends AbstractProcessor
{
    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function create(string $name): Response
    {
        if (with($name, fn ($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("TELEPHONY: Provided wrong arguments");
        }

        return Http::withQueryParameters([
            'name' => $name
        ])->post(normalizeUrl($this->prefix, '/'));

    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function delete(string $scheduleId): Response
    {
        if (with($scheduleId, fn ($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("TELEPHONY: Provided wrong arguments");
        }

        return Http::withQueryParameters([
            'scheduleId' => $scheduleId
        ])->delete(normalizeUrl($this->prefix));
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function get(int $scheduleId): Response
    {
        if (with($scheduleId, fn ($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("TELEPHONY: Provided wrong arguments");
        }

        return Http::withUrlParameters([
            'schedule_id' => $scheduleId
        ])->post(normalizeUrl($this->prefix, "/{schedule_id}"));
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
        if (with($scheduleId, fn ($value) => empty($value)) ||
            with($params, fn ($args) => count(array_filter($args, fn ($key, $value) => empty($value) || empty($key)), ARRAY_FILTER_USE_BOTH) > 0))
        {
            throw new TelephonyHandlerInputDataValidationException("TELEPHONY: Provided wrong arguments");
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
    public function deleteParameters(int $scheduleParametersId): Response
    {
        if (with($scheduleParametersId, fn ($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("TELEPHONY: Provided wrong arguments");
        }
        return Http::withUrlParameters([
            'schedule_parameters_id' => $scheduleParametersId
        ])->delete(normalizeUrl($this->prefix, "/parameters", "/{schedule_parameters_id}"));
    }
}