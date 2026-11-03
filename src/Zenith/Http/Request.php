<?php

declare(strict_types=1);

namespace Zenith\Http;

final class Request
{
    /** @param array<string, mixed> $query
     *  @param array<string, mixed> $body
     *  @param array<string, string> $headers
     *  @param array<string, mixed> $server
     */
    public function __construct(
        public readonly string $method,
        public readonly string $uri,
        public readonly array $query = [],
        public readonly array $body = [],
        public readonly array $headers = [],
        public readonly array $server = [],
    ) {
    }

    public static function fromGlobals(): self
    {
        $headers = function_exists('getallheaders') ? getallheaders() : [];

        return new self(
            method: strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET'),
            uri: $_SERVER['REQUEST_URI'] ?? '/',
            query: $_GET,
            body: $_POST,
            headers: is_array($headers) ? array_change_key_case($headers, CASE_LOWER) : [],
            server: $_SERVER,
        );
    }
}
