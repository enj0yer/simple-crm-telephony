<?php

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\TelephonyHandlerInputDataValidationException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use function Enj0yer\CrmTelephony\Helpers\normalizeUrl;

class ProcessPhones extends AbstractProcessor
{

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function getFromGroup(int $groupId): Response
    {

        if (with($groupId, fn ($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("TELEPHONY: Provided wrong arguments");
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
        if (with($groupId, fn ($value) => empty($value)) ||
            with($phones, fn ($args) => count(array_filter($args, fn ($value) => empty($value))) > 0))
        {
            throw new TelephonyHandlerInputDataValidationException("TELEPHONY: Provided wrong arguments");
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
        if (with($groupId, fn ($value) => empty($value)) ||
            with($phones, fn ($args) => count(array_filter($args, fn ($value) => empty($value))) > 0))
        {
            throw new TelephonyHandlerInputDataValidationException("TELEPHONY: Provided wrong arguments");
        }

        return Http::withUrlParameters([
            'phonegroup_id' => $groupId
        ])->withBody(
            json_encode($phones)
        )->delete(normalizeUrl($this->prefix, "/{phonegroup_id}"));
    }
}