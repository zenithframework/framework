<?php

declare(strict_types=1);

namespace Zenith\Contracts;

interface AuthManagerInterface
{
    public function check(): bool;

    public function id(): string|int|null;
}
