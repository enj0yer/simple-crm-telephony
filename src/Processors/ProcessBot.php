<?php

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\TelephonyHandlerInputDataValidationException;
use Enj0yer\CrmTelephony\Processors\AbstractProcessor;
use Enj0yer\CrmTelephony\Response\TelephonyResponse;
use Enj0yer\CrmTelephony\Response\TelephonyResponseFactory;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use function Enj0yer\CrmTelephony\Helpers\normalizeUrl;

class ProcessBot extends AbstractProcessor
{
    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function startInput(string $botId): TelephonyResponse
    {
        if (with($botId, fn($value) => empty($value)))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        $response = Http::withQueryParameters([
            "botid" => $botId
        ])->get(normalizeUrl($this->prefix, "/startinputid"));
        return TelephonyResponseFactory::createDefault($response);   
    }
    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function sound(string $botId, string|null $inputDtmfStep = null): TelephonyResponse
    {
        if (with($botId, fn($value) => empty($botId)))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        $response = Http::withQueryParameters([
            "bot_id" => $botId,
            "input_dtmf_step" => $inputDtmfStep
        ])->get(normalizeUrl($this->prefix, "/sound"));
        return TelephonyResponseFactory::createDefault($response);
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function soundJson(string $botId, string $inputDtmfStep): TelephonyResponse
    {
        if (with([$botId, $inputDtmfStep], fn($args) => count(array_filter($args, fn($value) => empty($value))) > 0))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        $response = Http::withQueryParameters([
            "bot_id" => $botId,
            "input_dtmf_step" => $inputDtmfStep
        ])->get(normalizeUrl($this->prefix, "/sound"));
        return TelephonyResponseFactory::createDefault($response);
    }

    /**
     * @throws TelephonyHandlerInputDataValidationException
     */
    public function answer(string $botId,
                           string $inputDtmfStep,
                           string $userAnswer,
                           string $dialTaskId,
                           string $callerId): TelephonyResponse
    {
        if (with([$botId, $inputDtmfStep, $userAnswer, $dialTaskId, $callerId], fn($args) => count(array_filter($args, fn($value) => empty($value))) > 0))
        {
            throw new TelephonyHandlerInputDataValidationException("Provided wrong arguments");
        }

        $response = Http::withQueryParameters([
            "bot_id" => $botId,
            "input_dtmf_step" => $inputDtmfStep,
            "user_answer" => $userAnswer,
            "dial_task_id" => $dialTaskId,
            "caller_id" => $callerId
        ])->get(normalizeUrl($this->prefix, "/answer"));
        return TelephonyResponseFactory::createDefault($response);
    }

}