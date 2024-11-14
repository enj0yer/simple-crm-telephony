<?php

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\TelephonyHandlerInputDataValidationException;
use Enj0yer\CrmTelephony\Helpers\UrlBuilder;
use Enj0yer\CrmTelephony\Response\TelephonyResponse;
use Enj0yer\CrmTelephony\Response\TelephonyResponseFactory;
use Illuminate\Support\Facades\Http;
use function Enj0yer\CrmTelephony\Helpers\isAllNonEmpty;
use function Enj0yer\CrmTelephony\Helpers\isAllPozitive;
use function Enj0yer\CrmTelephony\Helpers\isArrayWithOnlyNonEmptyKeysAndValues;

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
        ]), 'application/json')->post(UrlBuilder::new($this->prefix, '/'));
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
        ]), 'application/json')->patch(UrlBuilder::new($this->prefix, '/'));
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

        $url = UrlBuilder::new($this->prefix, "/{schedule_id}")
                         ->withUrlParameters(['schedule_id' => $scheduleId]);
        $response = Http::delete($url);
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

        $url = UrlBuilder::new($this->prefix, "/{schedule_id}")
                         ->withUrlParameters(['schedule_id' => $scheduleId]);
        $response = Http::get($url);
        return TelephonyResponseFactory::createDefault($response);
    }

    public function getAll(): TelephonyResponse
    {
        $response = Http::get(UrlBuilder::new($this->prefix, "/list"));
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

        $url = UrlBuilder::new($this->prefix, "/parameters", "/{schedule_id}")
                         ->withUrlParameters(['schedule_id' => $scheduleId]);
        $response = Http::withBody(json_encode(
            array_map(fn ($key) => ['key' => $key, 'value' => $params[$key]], array_keys($params))
        ), 'application/json')->post($url);
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

        $url = UrlBuilder::new($this->prefix, "/parameters", "/{schedule_parameters_id}")
                         ->withUrlParameters(['schedule_parameters_id' => $scheduleParametersId]);
        $response = Http::delete($url);
        return TelephonyResponseFactory::createDefault($response);
    }
}