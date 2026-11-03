<?php

declare(strict_types=1);

namespace Zenith\Routing;

use Zenith\Http\Request;
use Zenith\Http\Response;

interface RouterInterface
{
    public function dispatch(Request $request): Response;
}
