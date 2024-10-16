<?php

namespace Enj0yer\CrmTelephony\Processors;

use Enj0yer\CrmTelephony\Exceptions\TelephonyHandlerInputDataValidationException;
use Enj0yer\CrmTelephony\Helpers\UrlBuilder;
use Enj0yer\CrmTelephony\Processors\AbstractProcessor;
use Enj0yer\CrmTelephony\Response\TelephonyResponse;
use Enj0yer\CrmTelephony\Response\TelephonyResponseFactory;
use Illuminate\Support\Facades\Http;

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

        $url = UrlBuilder::new($this->prefix, "/startinputid")
                         ->withQueryParameters(["botid" => $botId]);
        $response = Http::get($url);
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

        $url = UrlBuilder::new($this->prefix, "/sound")
                         ->withQueryParameters(["bot_id" => $botId,
                                                "input_dtmf_step" => $inputDtmfStep]);
        $response = Http::get($url);
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

        $url = UrlBuilder::new($this->prefix, "/sound")
                         ->withQueryParameters(["bot_id" => $botId,
                                                "input_dtmf_step" => $inputDtmfStep]);
        $response = Http::get($url);
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

        $url = UrlBuilder::new($this->prefix, "/answer")
                         ->withQueryParameters(["bot_id" => $botId,
                                                "input_dtmf_step" => $inputDtmfStep,
                                                "user_answer" => $userAnswer,
                                                "dial_task_id" => $dialTaskId,
                                                "caller_id" => $callerId]);
        $response = Http::get($url);
        return TelephonyResponseFactory::createDefault($response);
    }

}