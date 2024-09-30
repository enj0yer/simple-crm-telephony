<?php

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\TelephonyHandlerInputDataValidationException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use function Enj0yer\CrmTelephony\Helpers\normalizeUrl;

class ProcessRecords extends AbstractProcessor
{
    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function uploadRecord($file): Response
    {
        if (with($file, fn ($value) => is_null($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("TELEPHONY: Provided wrong arguments");
        }

        return Http::withBody($file, 'multipart/form-data')
            ->post(normalizeUrl($this->prefix, "/upload_record"));
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function deleteRecord(string $recordId): Response
    {
        if (with($recordId, fn ($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("TELEPHONY: Provided wrong arguments");
        }

        return Http::withUrlParameters([
            'record_id' => $recordId
        ])->delete(normalizeUrl($this->prefix, "/delete_record", "/{record_id}"));
    }
}
