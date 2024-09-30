<?php

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\TelephonyHandlerInputDataValidationException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use function Enj0yer\CrmTelephony\Helpers\normalizeUrl;

class ProcessRetryLogic extends AbstractProcessor
{
    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function create(string $name, string $description = ""): Response
    {
        if (with($name, fn ($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("TELEPHONY: Provided wrong arguments");
        }

        return Http::withBody(json_encode([
            'name' => $name,
            'description' => $description,
        ]))->post(normalizeUrl($this->prefix, "/logic"));
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function update(int $logicId, string $name = "", string $description = ""): Response
    {
        if (with($logicId, fn ($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("TELEPHONY: Provided wrong arguments");
        }

        return Http::withUrlParameters([
            'retrylogic_id' => $logicId
        ])->withBody(json_encode([
            'name' => $name,
            'description' => $description,
        ]))->patch(normalizeUrl($this->prefix, "/logic", "/{retrylogic_id}"));
    }

    public function delete(int $logicId): Response
    {
        if (with($logicId, fn ($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("TELEPHONY: Provided wrong arguments");
        }
        return Http::withUrlParameters([
            'retrylogic_id' => $logicId
        ])->delete(normalizeUrl($this->prefix, "/logic", "/{retrylogic_id}"));
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function get(int $logicId): Response
    {
        if (with($logicId, fn ($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("TELEPHONY: Provided wrong arguments");
        }
        return Http::withUrlParameters([
            'retrylogic_id' => $logicId
        ])->get(normalizeUrl($this->prefix, "/logic", "/{retrylogic_id}"));
    }

    public function getAll(): Response
    {
        return Http::get(normalizeUrl($this->prefix, "/logic", "/list"));
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function getParameters(int $logicId): Response
    {
        if (with($logicId, fn ($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("TELEPHONY: Provided wrong arguments");
        }

        return Http::withUrlParameters([
            'retry_logic_id' => $logicId
        ])->get(normalizeUrl($this->prefix, "/parameters", "/list", "/{retrylogic_id}"));
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function createParameters(int $logicId, array $parameters): Response
    {
        if (with($logicId, fn ($value) => empty($value)) && with($parameters, fn ($params) => count(array_filter(array_keys($params), fn ($key) => !is_string($key))) > 0))
        {
            throw new TelephonyHandlerInputDataValidationException("TELEPHONY: Provided wrong arguments");
        }

        return Http::withUrlParameters([
            "retry_logic_id" => $logicId
        ])->withBody(json_encode(
            with($parameters, fn ($params) => array_map(fn ($key) => [
                "retry_logic_id" => $logicId,
                "param_key" => $key,
                "param_value" => $parameters[$key]
            ], array_keys($params)))
        ))
          ->post(normalizeUrl($this->prefix, "/parameters", "/{retry_logic_id}"));
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function updateParameter(int $logicId, int $paramId, string $key, string $value): Response
    {
        if (with([$logicId, $paramId, $key, $value], fn ($params) => count(array_filter($params, fn ($value) => empty($value))) > 0))
        {
            throw new TelephonyHandlerInputDataValidationException("TELEPHONY: Provided wrong arguments");
        }

        return Http::withUrlParameters([
            "param_id" => $paramId
        ])->withBody(json_encode([
            "retry_logic_id" => $logicId,
            "param_key" => $key,
            "param_value" => $value
        ]))->patch(normalizeUrl($this->prefix, "/parameters", "/{param_id}"));
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function deleteParameter(int $paramId): Response
    {
        if (with($paramId, fn ($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("TELEPHONY: Provided wrong arguments");
        }

        return Http::withUrlParameters([
            "param_id" => $paramId
        ])->delete(normalizeUrl($this->prefix, "/parameters", "/{param_id}"));
    }

}