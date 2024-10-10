<?php

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\TelephonyHandlerInputDataValidationException;
use Enj0yer\CrmTelephony\Response\TelephonyResponse;
use Enj0yer\CrmTelephony\Response\TelephonyResponseFactory;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use function Enj0yer\CrmTelephony\Helpers\isAllNonEmpty;
use function Enj0yer\CrmTelephony\Helpers\isAllPozitive;
use function Enj0yer\CrmTelephony\Helpers\normalizeUrl;

class ProcessPhoneGroups extends AbstractProcessor
{

    public function getAll(): TelephonyResponse
    {
        $response =Http::get(normalizeUrl($this->prefix, "/list"));
        return TelephonyResponseFactory::createMultiple($response);
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function create(string $name, string $description = ""): TelephonyResponse
    {
        if (!isAllNonEmpty($name))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `name` must be non empty");
        }

        $response = Http::withBody(json_encode([
            'name' => $name,
            'description' => $description,
        ]))->post(normalizeUrl($this->prefix, '/'));
        return TelephonyResponseFactory::createDefault($response);
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function delete(int $groupId): TelephonyResponse
    {
        if (!isAllPozitive($groupId))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `groupId` must be greater than zero, provided $groupId");
        }

        $response = Http::withUrlParameters([
            'phonegroup_id' => $groupId
        ])->delete(normalizeUrl($this->prefix, "/{phonegroup_id}"));
        return TelephonyResponseFactory::createDefault($response);
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function get(int $groupId): TelephonyResponse
    {
        if (!isAllPozitive($groupId))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `groupId` must be greater than zero, provided $groupId");
        }
        $response = Http::withUrlParameters([
            'phonegroup_id' => $groupId
        ])->get(normalizeUrl($this->prefix, "/{phonegroup_id}"));
        return TelephonyResponseFactory::createDefault($response);
    }

    public function update(int $groupId, string $newName = "", string $newDescription = ""): TelephonyResponse
    {
        if (!isAllPozitive($groupId))
        {
            throw new TelephonyHandlerInputDataValidationException("Parameter `groupId` must be greater than zero, provided $groupId");
        }

        $response = Http::withUrlParameters([
            'phonegroup_id' => $groupId,
        ])->withBody(json_encode([
            'name' => $newName,
            'description' => $newDescription,
        ]))->patch(normalizeUrl($this->prefix, "/{phonegroup_id}"));
        return TelephonyResponseFactory::createDefault($response);
    }
}
