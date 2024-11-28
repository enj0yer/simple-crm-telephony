<?php

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\TelephonyHandlerInputDataValidationException;
use Enj0yer\CrmTelephony\Helpers\UrlBuilder;
use Enj0yer\CrmTelephony\Processors\AbstractProcessor;
use Enj0yer\CrmTelephony\Response\TelephonyResponse;
use Enj0yer\CrmTelephony\Response\TelephonyResponseFactory;
use Illuminate\Support\Facades\Http;
use function Enj0yer\CrmTelephony\Helpers\isAllNonEmpty;
use function Enj0yer\CrmTelephony\Helpers\isAllPozitive;

class ProcessIntegrations extends AbstractProcessor
{
    public function getAll(): TelephonyResponse
    {
        $result = Http::get(UrlBuilder::new($this->prefix, '/list'));
        return TelephonyResponseFactory::createMultiple($result);
    }

    public function create(string $name, string $serviceUrl, string $description = "", array $defaultData = [], array $defaultHeader = []): TelephonyResponse
    {
        if (!isAllNonEmpty($name, $serviceUrl)) {
            throw new TelephonyHandlerInputDataValidationException("Parameter name and serviceUrl must be non empty");
        }

        $response = Http::withBody(json_encode([
            'name' => $name,
            'description' => $description,
            'service_url' => $serviceUrl,
            'default_data_json_str' => json_encode($defaultData),
            'default_header_json_str' => json_encode($defaultHeader)
        ]))->post(UrlBuilder::new($this->prefix, "/intergation"));
        return TelephonyResponseFactory::createDefault($response);
    }
    
    public function delete(int $integrationId): TelephonyResponse
    {
        if (!isAllPozitive($integrationId)) {
            throw new TelephonyHandlerInputDataValidationException("Parameter integrationId must be greater than zero, provided '$integrationId'");
        }
        $response = Http::delete(
            UrlBuilder::new($this->prefix, "/intergation")
                ->withQueryParameters([
                    'intergation_id' => $integrationId
                ]
            )
        );
        return TelephonyResponseFactory::createDefault($response);
    }

    public function joinsDtmf(): TelephonyResponse
    {
        $response = Http::get(UrlBuilder::new($this->prefix, "/joins_dtmf"));
        return TelephonyResponseFactory::createMultiple($response);
    }
}