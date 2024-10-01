<?php

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\TelephonyHandlerInputDataValidationException;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use function Enj0yer\CrmTelephony\Helpers\normalizeUrl;

class ProcessOperators extends AbstractProcessor
{
    public function getAll(): Response
    {
        return Http::get(normalizeUrl($this->prefix, "/list"));
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function create(string $extension, string $password, string $userContext = 'default'): Response
    {

        if (with([$extension, $password, $userContext], fn ($args) => count(array_filter($args, fn ($value) => empty($value))) > 0))
        {
            throw new TelephonyHandlerInputDataValidationException("TELEPHONY: Provided wrong arguments");
        }
        return Http::withBody(json_encode([
            'extension' => $extension,
            'password' => $password,
            'user_context' => $userContext
        ]))->post(normalizeUrl($this->prefix, '/'));
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function delete(string $extension): Response
    {
        if (with($extension, fn ($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("TELEPHONY: Provided wrong arguments");
        }

        return Http::withUrlParameters([
            'extension' => $extension
        ])->delete(normalizeUrl($this->prefix, "/{extension}"));
    }

    public function replace(string $extension, string $password, string $userContext = 'default'): Response
    {
        if (with([$extension, $password, $userContext], fn ($args) => count(array_filter($args, fn ($value) => empty($value))) > 0))
        {
            throw new TelephonyHandlerInputDataValidationException("TELEPHONY: Provided wrong arguments");
        }
        return Http::withBody(json_encode([
            'extension' => $extension,
            'password' => $password,
            'user_context' => $userContext
        ]))->put(normalizeUrl($this->prefix, '/'));
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function get(string $extension): Response
    {
        if (with($extension, fn ($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("TeLEPHONY: Provided wrong arguments");
        }

        return Http::withUrlParameters([
            "extension" => $extension
        ])->get(normalizeUrl($this->prefix, "/{extension}"));
    }
}