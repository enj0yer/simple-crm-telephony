<?php

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\TelephonyHandlerInputDataValidationException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use function Enj0yer\CrmTelephony\Helpers\normalizeUrl;

class ProcessPhoneGroups extends AbstractProcessor
{

    public function getAll(): Response
    {
        return Http::get(normalizeUrl($this->prefix, "/list"));
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function create(string $name, string $description = ""): Response
    {
        if (with($name, fn ($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("TELEPHONY: Provided wrong arguments");
        }

        return Http::withBody(json_encode([
            'name' => $name,
            'description' => $description,
        ]))->post(normalizeUrl($this->prefix, '/'));
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function delete(int $groupId): Response
    {
        if (with($groupId, fn ($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("TELEPHONY: Provided wrong arguments");
        }

        return Http::withUrlParameters([
            'phonegroup_id' => $groupId
        ])->delete(normalizeUrl($this->prefix, "/{phonegroup_id}"));
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function get(int $groupId): Response
    {
        if (with($groupId, fn ($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("TELEPHONY: Provided wrong arguments");
        }
        return Http::withUrlParameters([
            'phonegroup_id' => $groupId
        ])->get(normalizeUrl($this->prefix, "/{phonegroup_id}"));
    }

    public function update(int $groupId, string $newName = "", string $newDescription = ""): Response
    {
        if (with($groupId, fn ($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("TELEPHONY: Provided wrong arguments");
        }

        return Http::withUrlParameters([
            'phonegroup_id' => $groupId,
        ])->withBody(json_encode([
            'name' => $newName,
            'description' => $newDescription,
        ]))->patch(normalizeUrl($this->prefix, "/{phonegroup_id}"));
    }
}
