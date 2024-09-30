<?php

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\TelephonyHandlerInputDataValidationException;
use Enj0yer\CrmTelephony\Helpers\BotInputDtmfStepSchema;
use Enj0yer\CrmTelephony\Processors\AbstractProcessor;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use function Enj0yer\CrmTelephony\Helpers\normalizeUrl;

class ProcessNodeOperation extends AbstractProcessor
{
    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function create(string $name,
                           int $botId,
                           string $soundName,
                           BotInputDtmfStepSchema $dtmfStepSchema,
                           int|null $waitExtEn): Response
    {
        if (with([$name, $botId, $soundName], fn($args) => count(array_filter($args, fn($value) => empty($value))) > 0))
        {
            throw new TelephonyHandlerInputDataValidationException("TELEPHONY: Provided wrong arguments");
        }

        return Http::withBody(json_encode([
            array_merge([
                "name" => $name,
                "bot_id" => $botId,
                "sound_name" => $soundName,
                "waitexten" => $waitExtEn
            ], $dtmfStepSchema->getActions())
        ]))->post(normalizeUrl($this->prefix, "/"));
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function delete(string $botId, string $inputDtmfNodeId): Response
    {
        if (with([$botId, $inputDtmfNodeId], fn($args) => count(array_filter($args, fn($value) => empty($value))) > 0))
        {
            throw new TelephonyHandlerInputDataValidationException("TELEPHONY: Provided wrong arguments");
        }

        return Http::withQueryParameters([
            "bot_id" => $botId,
            "input_dtmf_node_id" => $inputDtmfNodeId
        ])->delete(normalizeUrl($this->prefix, "/"));
    }
}