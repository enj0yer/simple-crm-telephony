<?php

namespace Enj0yer\CrmTelephony\Helpers;

class UrlBuilder 
{
    private $url = "";

    private function __construct($url) 
    {
        $this->url = $url;
    }

    public static function new(string ...$url): static
    {
        $url = static::normalizeUrl(...$url);
        return new static($url);
    }

    public static function normalizeUrl(string ...$url): string
    {
        $joinedUrl = implode("/", $url);
        if (str_contains($joinedUrl, "https")) {
            return preg_replace("#(?<!https:)/{2,}#", "/", $joinedUrl);
        } else {
            return preg_replace("#(?<!http:)/{2,}#", "/", $joinedUrl);
        }
    }

    private static function addQueryParameters(string $url, array $parameters): string
    {
        return static::removeSingleTrailingSlash($url) . "?" . with($parameters, function ($params) {
            $result = [];
            foreach ($params as $key => $value) {
                $result[] = (string) $key ."=". (string) $value;
            }
            return implode("&", $result);
        });
    }

    private static function addUrlParameters(string $url, array $parameters): string
    {
        $new = static::removeSingleTrailingSlash($url);
        foreach ($parameters as $key => $value) {
            if (str_contains($new, "{".(string) $key."}")) {
                $new = str_replace("{".(string) $key."}", $value, $new);
            }
        }
        return $new;
    }

    private static function removeSingleTrailingSlash(string $url): string
    {
        return str_ends_with('/', $url) ? substr($url, 0, -1) : $url;
    }

    public function withUrlParameters(array $parameters): self
    {
        $this->url = static::addUrlParameters($this->url, $parameters);
        return $this;
    }

    public function withQueryParameters(array $parameters): self
    {
        $this->url = static::addQueryParameters($this->url, $parameters);
        return $this;
    }

    public function get(): string
    {
        return $this->url;
    }

    public function __tostring()
    {
        return $this->url;
    }
}