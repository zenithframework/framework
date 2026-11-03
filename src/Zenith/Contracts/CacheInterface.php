<?php

declare(strict_types=1);

namespace Zenith\Contracts;

interface CacheInterface
{
    public function get(string $key, mixed $default = null): mixed;

    public function set(string $key, mixed $value, ?int $ttl = null): void;

    public function delete(string $key): bool;
}
