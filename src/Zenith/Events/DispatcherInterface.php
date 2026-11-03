<?php

declare(strict_types=1);

namespace Zenith\Events;

interface DispatcherInterface
{
    public function dispatch(object $event): object;
}
