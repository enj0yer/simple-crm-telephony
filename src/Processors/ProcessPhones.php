<?php

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\TelephonyHandlerInputDataValidationException;
use Enj0yer\CrmTelephony\Response\TelephonyResponse;
use Enj0yer\CrmTelephony\Response\TelephonyResponseFactory;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use function Enj0yer\CrmTelephony\Helpers\normalizeUrl;
use function Enj0yer\CrmTelephony\Helpers\isAllNonEmpty;
use function Enj0yer\CrmTelephony\Helpers\isAllPozitive;

class ProcessPhones extends AbstractProcessor
{

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function getFromGroup(int $groupId): TelephonyResponse
    {

        if (!isAllPozitive($groupId))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `groupId` must be greater than zero, provided $groupId");
        }
        
        $response = Http::withUrlParameters([
            'phonegroup_id' => $groupId
            ])->get(normalizeUrl($this->prefix, "/{phonegroup_id}"));
        return TelephonyResponseFactory::createMultiple($response);
    }
        
        /**
         * @throws TelephonyHandlerInputDataValidationException
         */
        public function addToGroup(int $groupId, ...$phones): TelephonyResponse
        {
            if (!isAllPozitive($groupId))
            {  
                throw new TelephonyHandlerInputDataValidationException("Parameter `groupId` must be greater than zero, provided $groupId");
            }
            if (!isAllNonEmpty($phones))
            {
                throw new TelephonyHandlerInputDataValidationException("Parameter `phones` must contains non empty values");
            }

            $response = Http::withUrlParameters([
                'phonegroup_id' => $groupId
            ])->withBody(
                json_encode(array_map(fn ($element) => ['phone' => $element], $phones))
            )->post(normalizeUrl($this->prefix, "/{phonegroup_id}"));
            return TelephonyResponseFactory::createDefault($response);
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function removeFromGroup(int $groupId, ...$phones): TelephonyResponse
    {
        if (!isAllPozitive($groupId))
        {  
            throw new TelephonyHandlerInputDataValidationException("Parameter `groupId` must be greater than zero, provided $groupId");
        }
        if (!isAllNonEmpty($phones))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `phones` must contains non empty values");
        }

        $response = Http::withUrlParameters([
            'phonegroup_id' => $groupId
        ])->withBody(
            json_encode($phones)
        )->delete(normalizeUrl($this->prefix, "/{phonegroup_id}"));
        return TelephonyResponseFactory::createDefault($response);
    }
}