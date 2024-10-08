<?php

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\TelephonyHandlerInputDataValidationException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use function Enj0yer\CrmTelephony\Helpers\normalizeUrl;
use function Enj0yer\CrmTelephony\Helpers\isAllNonEmpty;
use function Enj0yer\CrmTelephony\Helpers\isAllPozitive;

class ProcessPhones extends AbstractProcessor
{

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function getFromGroup(int $groupId): Response
    {

        if (!isAllPozitive($groupId))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `groupId` must be greater than zero, provided $groupId");
        }
        
        return Http::withUrlParameters([
            'phonegroup_id' => $groupId
            ])->get(normalizeUrl($this->prefix, "/{phonegroup_id}"));
        }
        
        /**
         * @throws TelephonyHandlerInputDataValidationException
         */
        public function addToGroup(int $groupId, ...$phones): Response
        {
            if (!isAllPozitive($groupId))
            {  
                throw new TelephonyHandlerInputDataValidationException("Parameter `groupId` must be greater than zero, provided $groupId");
            }
            if (!isAllNonEmpty($phones))
            {
                throw new TelephonyHandlerInputDataValidationException("Parameter `phones` must contains non empty values");
            }

            return Http::withUrlParameters([
                'phonegroup_id' => $groupId
            ])->withBody(
                json_encode(array_map(fn ($element) => ['phone' => $element], $phones))
            )->post(normalizeUrl($this->prefix, "/{phonegroup_id}"));
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function removeFromGroup(int $groupId, ...$phones): Response
    {
        if (!isAllPozitive($groupId))
        {  
            throw new TelephonyHandlerInputDataValidationException("Parameter `groupId` must be greater than zero, provided $groupId");
        }
        if (!isAllNonEmpty($phones))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `phones` must contains non empty values");
        }

        return Http::withUrlParameters([
            'phonegroup_id' => $groupId
        ])->withBody(
            json_encode($phones)
        )->delete(normalizeUrl($this->prefix, "/{phonegroup_id}"));
    }
}