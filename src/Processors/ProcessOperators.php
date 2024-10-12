<?php

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\TelephonyHandlerInputDataValidationException;
use Enj0yer\CrmTelephony\Response\TelephonyResponse;
use Enj0yer\CrmTelephony\Response\TelephonyResponseFactory;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use function Enj0yer\CrmTelephony\Helpers\normalizeUrl;

class ProcessOperators extends AbstractProcessor
{
    public function getAll(): TelephonyResponse
    {
        $response = Http::get(normalizeUrl($this->prefix, "/list"));
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
        ]))->post(normalizeUrl($this->prefix, '/'));
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

        $response = Http::withUrlParameters([
            'extension' => $extension
        ])->delete(normalizeUrl($this->prefix, "/{extension}"));
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
        ]))->put(normalizeUrl($this->prefix, '/'));
        return TelephonyResponseFactory::createDefault($response);
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function get(string $extension): TelephonyResponse
    {
        if (with($extension, fn ($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("TeLEPHONY: Provided wrong arguments");
        }
        
        $response = Http::withUrlParameters([
            "extension" => $extension
        ])->get(normalizeUrl($this->prefix, "/{extension}"));
        return TelephonyResponseFactory::createDefault($response);
    }
}