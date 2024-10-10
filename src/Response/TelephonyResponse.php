<?php

namespace Enj0yer\CrmTelephony\Response;
use Illuminate\Support\Collection;

class TelephonyResponse 
{
    private bool $statusCode;
    
    private array $data = [];

    private bool $multipleResponse;

    public function __construct(int $responseStatusCode, ?array $data, bool $multiple = false)
    {
        $this->statusCode = $responseStatusCode;
        $this->data = $data;
        $this->multipleResponse = $multiple;
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }

    public function successful(): bool
    {
        return $this->statusCode() >= 200 && $this->statusCode() < 300;
    }

    public function multiple(): bool
    {
        return $this->multipleResponse;
    }

    public function asCollection(): Collection
    {
        return new Collection($this->data);
    }

    public function asObject()
    {
        return json_decode(json_encode($this->data));
    }

    public function asArray(): array
    {
        return $this->data;
    }

    public function __tostring(): string
    {
        return $this->successful() ? "1" : "0";
    }
}