<?php 

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\TelephonyHandlerInputDataValidationException;
use Enj0yer\CrmTelephony\Helpers\UrlBuilder;
use Enj0yer\CrmTelephony\Response\TelephonyResponse;
use Enj0yer\CrmTelephony\Response\TelephonyResponseFactory;
use Illuminate\Support\Facades\Http;
use function Enj0yer\CrmTelephony\Helpers\isAllNonEmpty;
use function Enj0yer\CrmTelephony\Helpers\isAllPozitive;

class ProcessTrunks extends AbstractProcessor
{
    public function getAll(): TelephonyResponse
    {
        $response = Http::get(UrlBuilder::new($this->prefix, "/list"));
        return TelephonyResponseFactory::createMultiple($response);
    }
    
    public function create(string $name, string $description = "", string $parameters = ""): TelephonyResponse
    {
        if (!isAllNonEmpty($name)) {
            throw new TelephonyHandlerInputDataValidationException("Parameter name must be non empty, provided '$name'");
        }

        $response = Http::withBody(json_encode([
            'name' => $name,
            'description' => $description,
            'parameters' => $parameters
        ]))->post(UrlBuilder::new($this->prefix, "/"));
        return TelephonyResponseFactory::createDefault($response);
    }

    public function delete(int $trunkId): TelephonyResponse
    {
        if (!isAllPozitive($trunkId)) {
            throw new TelephonyHandlerInputDataValidationException("Parameter trunkId must contains pozitive value, provided '$trunkId'");
        }
        $response = Http::delete(
            UrlBuilder::new($this->prefix, "/")
                ->withQueryParameters([
                    'trunk_id' => $trunkId
                ])
        );
        return TelephonyResponseFactory::createDefault($response);
    }
}