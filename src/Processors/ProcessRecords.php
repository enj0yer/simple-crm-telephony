<?php

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\TelephonyHandlerInputDataValidationException;
use Enj0yer\CrmTelephony\Helpers\UrlBuilder;
use Enj0yer\CrmTelephony\Response\TelephonyResponse;
use Enj0yer\CrmTelephony\Response\TelephonyResponseFactory;
use Illuminate\Support\Facades\Http;

class ProcessRecords extends AbstractProcessor
{
    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function uploadRecord($file): TelephonyResponse
    {
        if (with($file, fn ($value) => is_null($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        $response = Http::withBody($file, 'multipart/form-data')
            ->post(UrlBuilder::new($this->prefix, "/upload_record"));
        return TelephonyResponseFactory::createDefault($response);
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function deleteRecord(string $recordId): TelephonyResponse
    {
        if (with($recordId, fn ($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }
        $url = UrlBuilder::new($this->prefix, "/delete_record", "/{record_id}")
                         ->withUrlParameters(['record_id' => $recordId]);
        $response = Http::delete($url);
        return TelephonyResponseFactory::createDefault($response);
    }
}
