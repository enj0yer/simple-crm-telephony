<?php

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\TelephonyHandlerInputDataValidationException;
use Enj0yer\CrmTelephony\Response\TelephonyResponse;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use function Enj0yer\CrmTelephony\Helpers\isAllNonEmpty;
use function Enj0yer\CrmTelephony\Helpers\isAllPozitive;
use function Enj0yer\CrmTelephony\Helpers\isAssocArrayWithStringNonEmptyKeys;
use function Enj0yer\CrmTelephony\Helpers\normalizeUrl;

class ProcessRetryLogic extends AbstractProcessor
{
    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function create(string $name, string $description = ""): Response
    {
        if (!isAllNonEmpty($name))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `name` must be non empty string");
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
        if (!isAllPozitive($logicId))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `logicId` must be greater than zero, provided $logicId");
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
        if (!isAllPozitive($logicId))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `logicId` must be greater than zero, provided $logicId");
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
        if (!isAllPozitive($logicId))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `logicId` must be greater than zero, provided $logicId");
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
        if (!isAllPozitive($logicId))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `logicId` must be greater than zero, provided $logicId");
        }

        return Http::withUrlParameters([
            'retry_logic_id' => $logicId
        ])->get(normalizeUrl($this->prefix, "/parameters", "/list", "/{retry_logic_id}"));
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function createParameters(int $logicId, array $parameters): Response
    {
        if (!isAllPozitive($logicId))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `logicId` must be greater than zero, provided $logicId");
        }

        if (!isAssocArrayWithStringNonEmptyKeys($parameters))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `parameters` must be an assosiative array with non-empty keys strings");
        }
        return Http::withUrlParameters([
            "retry_logic_id" => $logicId
        ])->withBody(json_encode(
            with($parameters, fn ($params) => array_map(fn ($key) => [
                "retry_logic_id" => $logicId,
                "param_key" => $key,
                "param_value" => $params[$key]
            ], array_keys($params)))
        ))
          ->post(normalizeUrl($this->prefix, "/parameters", "/{retry_logic_id}"));
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function updateParameter(int $logicId, int $paramId, string $key, string $value): Response
    {
        if (!isAllPozitive($logicId, $paramId))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameters `logicId` and `paramId` must be greater than zero, provided $logicId and $paramId");
        }

        if (!isAllNonEmpty($key))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `key` must be non empty");
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
        if (!isAllNonEmpty())
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `paramId` must be greater than zero, provided $paramId");
        }

        return Http::withUrlParameters([
            "param_id" => $paramId
        ])->delete(normalizeUrl($this->prefix, "/parameters", "/{param_id}"));
    }

}