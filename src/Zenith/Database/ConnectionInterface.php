<?php

declare(strict_types=1);

namespace Zenith\Database;

interface ConnectionInterface
{
    /** @param array<string, mixed> $parameters */
    public function execute(string $sql, array $parameters = []): int;

    /** @param array<string, mixed> $parameters
     *  @return array<array<string, mixed>>
     */
    public function fetchAll(string $sql, array $parameters = []): array;
}
