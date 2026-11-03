<?php

declare(strict_types=1);

namespace Zenith\Http;

interface KernelInterface
{
    public function handle(Request $request): Response;
}
