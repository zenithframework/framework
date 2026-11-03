<?php

declare(strict_types=1);

namespace Zenith\Http;

final class Response
{
    /** @param array<string, string> $headers */
    public function __construct(
        public readonly string $content = '',
        public readonly int $status = 200,
        public readonly array $headers = ['content-type' => 'text/plain; charset=utf-8'],
    ) {
    }

    public function send(): void
    {
        http_response_code($this->status);

        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value);
        }

        echo $this->content;
    }
}
