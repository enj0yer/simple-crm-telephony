<?php

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\TelephonyHandlerInputDataValidationException;
use Enj0yer\CrmTelephony\Helpers\UrlBuilder;
use Enj0yer\CrmTelephony\Response\TelephonyResponse;
use Enj0yer\CrmTelephony\Response\TelephonyResponseFactory;
use Illuminate\Support\Facades\Http;
use function Enj0yer\CrmTelephony\Helpers\isAllNonEmpty;
use function Enj0yer\CrmTelephony\Helpers\isAllPozitive;
use function Enj0yer\CrmTelephony\Helpers\isAssocArrayWithStringNonEmptyKeys;

class ProcessRetryLogic extends AbstractProcessor
{
    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function create(string $name, string $description = ""): TelephonyResponse
    {
        if (!isAllNonEmpty($name))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `name` must be non empty string");
        }

        $response = Http::withBody(json_encode([
            'name' => $name,
            'description' => $description,
        ]), 'application/json')->post(UrlBuilder::new($this->prefix, "/logic"));
        return TelephonyResponseFactory::createDefault($response);
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function update(int $logicId, string $name = "", string $description = ""): TelephonyResponse
    {
        if (!isAllPozitive($logicId))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `logicId` must be greater than zero, provided $logicId");
        }

        $url = UrlBuilder::new($this->prefix, "/logic", "/{retrylogic_id}")
                         ->withUrlParameters(['retrylogic_id' => $logicId]);
        $response = Http::withBody(json_encode([
            'name' => $name,
            'description' => $description,
        ]), 'application/json')->patch($url);
        return TelephonyResponseFactory::createDefault($response);
    }

    public function delete(int $logicId): TelephonyResponse
    {
        if (!isAllPozitive($logicId))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `logicId` must be greater than zero, provided $logicId");
        }

        $url = UrlBuilder::new($this->prefix, "/logic", "/{retrylogic_id}")
                         ->withUrlParameters(['retrylogic_id' => $logicId]);
        $response = Http::delete($url);
        return TelephonyResponseFactory::createDefault($response);
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function get(int $logicId): TelephonyResponse
    {
        if (!isAllPozitive($logicId))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `logicId` must be greater than zero, provided $logicId");
        }

        $url = UrlBuilder::new($this->prefix, "/logic", "/{retrylogic_id}")
                         ->withUrlParameters(['retrylogic_id' => $logicId]);
        $response = Http::get($url);
        return TelephonyResponseFactory::createDefault($response);
    }

    public function getAll(): TelephonyResponse
    {
        $response = Http::get(UrlBuilder::new($this->prefix, "/logic", "/list"));
        return TelephonyResponseFactory::createMultiple($response);
    }
 
    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function getParameters(int $logicId): TelephonyResponse
    {
        if (!isAllPozitive($logicId))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `logicId` must be greater than zero, provided $logicId");
        }

        $url = UrlBuilder::new($this->prefix, "/parameters", "/list", "/{retry_logic_id}")
                         ->withUrlParameters(['retry_logic_id' => $logicId]);
        $response = Http::get($url);
        return TelephonyResponseFactory::createMultiple($response);
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function createParameters(int $logicId, array $parameters): TelephonyResponse
    {
        if (!isAllPozitive($logicId))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `logicId` must be greater than zero, provided $logicId");
        }

        if (!isAssocArrayWithStringNonEmptyKeys($parameters))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `parameters` must be an assosiative array with non-empty keys strings");
        }

        $url = UrlBuilder::new($this->prefix, "/parameters", "/{retry_logic_id}")
                         ->withUrlParameters(["retry_logic_id" => $logicId]);
        $response = Http::withBody(json_encode(
            with($parameters, fn ($params) => array_map(fn ($key) => [
                "retry_logic_id" => $logicId,
                "param_key" => $key,
                "param_value" => $params[$key]
            ], array_keys($params)))
        ), 'application/json')
          ->post($url);
          return TelephonyResponseFactory::createDefault($response);
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function updateParameter(int $logicId, int $paramId, string $key, string $value): TelephonyResponse
    {
        if (!isAllPozitive($logicId, $paramId))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameters `logicId` and `paramId` must be greater than zero, provided $logicId and $paramId");
        }

        if (!isAllNonEmpty($key))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `key` must be non empty");
        }

        $url = UrlBuilder::new($this->prefix, "/parameters", "/{param_id}")
                         ->withUrlParameters(["param_id" => $paramId]);
        $response = Http::withBody(json_encode([
            "retry_logic_id" => $logicId,
            "param_key" => $key,
            "param_value" => $value
        ]), 'application/json')->patch($url);
        return TelephonyResponseFactory::createDefault($response);
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function deleteParameter(int $paramId): TelephonyResponse
    {
        if (!isAllNonEmpty())
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `paramId` must be greater than zero, provided $paramId");
        }

        $url = UrlBuilder::new($this->prefix, "/parameters", "/{param_id}")
                         ->withUrlParameters(["param_id" => $paramId]);
        $response = Http::delete($url);
        return TelephonyResponseFactory::createDefault($response);
    }

}