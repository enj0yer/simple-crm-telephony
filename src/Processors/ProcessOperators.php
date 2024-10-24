<?php

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\TelephonyHandlerInputDataValidationException;
use Enj0yer\CrmTelephony\Helpers\UrlBuilder;
use Enj0yer\CrmTelephony\Response\TelephonyResponse;
use Enj0yer\CrmTelephony\Response\TelephonyResponseFactory;
use Illuminate\Support\Facades\Http;

class ProcessOperators extends AbstractProcessor
{
    public function getAll(): TelephonyResponse
    {
        $response = Http::get(UrlBuilder::new($this->prefix, "/list"));
        return TelephonyResponseFactory::createMultiple($response);
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function create(string $extension, string $password, string $userContext = 'default'): TelephonyResponse
    {

        if (with([$extension, $password, $userContext], fn ($args) => count(array_filter($args, fn ($value) => empty($value))) > 0))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }
        $response = Http::withBody(json_encode([
            'extension' => $extension,
            'password' => $password,
            'user_context' => $userContext
        ]), 'application/json')->post(UrlBuilder::new($this->prefix, '/'));
        return TelephonyResponseFactory::createDefault($response);
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function delete(string $extension): TelephonyResponse
    {
        if (with($extension, fn ($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        $url = UrlBuilder::new($this->prefix, "/{extension}")
                         ->withUrlParameters(['extension' => $extension]);
        $response = Http::delete($url);
        return TelephonyResponseFactory::createDefault($response);
    }

    public function replace(string $extension, string $password, string $userContext = 'default'): TelephonyResponse
    {
        if (with([$extension, $password, $userContext], fn ($args) => count(array_filter($args, fn ($value) => empty($value))) > 0))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }
        $response = Http::withBody(json_encode([
            'extension' => $extension,
            'password' => $password,
            'user_context' => $userContext
        ]), 'application/json')->put(UrlBuilder::new($this->prefix, '/'));
        return TelephonyResponseFactory::createDefault($response);
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function get(string $extension): TelephonyResponse
    {
        if (with($extension, fn ($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }
        
        $url = UrlBuilder::new($this->prefix, "/{extension}")
                         ->withUrlParameters(["extension" => $extension]);
        $response = Http::get($url);
        return TelephonyResponseFactory::createDefault($response);
    }
}