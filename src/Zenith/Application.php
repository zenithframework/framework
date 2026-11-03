<?php

declare(strict_types=1);

namespace Zenith;

use Zenith\Container\ContainerInterface;

/** Minimal application entry point for the Zenith runtime. */
final class Application
{
    public function __construct(
        private readonly ContainerInterface $container,
    ) {
    }

    public function container(): ContainerInterface
    {
        return $this->container;
    }
}
