<?php

namespace Enj0yer\CrmTelephony\Response;
use Illuminate\Support\Collection;

class TelephonyResponse 
{
    private bool $success;
    
    private array $data = [];

    private bool $multipleResponse;

    public function __construct(bool $success, array $data, bool $multiple = false)
    {
        $this->success = $success;
        $this->data = $data;
        $this->multipleResponse = $multiple;
    }

    public function successful(): bool
    {
        return $this->success;
    }

    public function multiple(): bool
    {
        return $this->multipleResponse;
    }

    public function asCollection(): Collection
    {
        return new Collection($this->data);
    }

    public function asObject(): \stdClass
    {
        return (object) $this->data;
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