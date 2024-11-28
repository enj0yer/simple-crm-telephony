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

class ProcessBlackList extends AbstractProcessor
{
    public function getAll(): TelephonyResponse
    {
        $response = Http::get(UrlBuilder::new($this->prefix, "/"));
        return TelephonyResponseFactory::createMultiple($response);
    }

    public function addToBlackList(string ...$phones): TelephonyResponse
    {
        if (!isAllNonEmpty(...$phones)) {
            throw new TelephonyHandlerInputDataValidationException("All phones must be non empty strings");
        }

        $response = Http::withBody(json_encode(
            array_map(fn ($item) => ['phone' => $item], $phones))
        )->post(UrlBuilder::new($this->prefix, '/'));

        return TelephonyResponseFactory::createDefault($response);
    }

    public function removeFromBlackList(int $phoneId): TelephonyResponse
    {
        if (!isAllPozitive($phoneId)) {
            throw new TelephonyHandlerInputDataValidationException("Parameter phoneId must be pozitive, provided '$phoneId'");
        }

        $response = Http::delete(
            UrlBuilder::new($this->prefix, '/')
                ->withQueryParameters([
                    'phone_id' => $phoneId
                ]
            )
        );
        return TelephonyResponseFactory::createDefault($response);
    }
}