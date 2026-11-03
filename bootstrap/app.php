<?php

declare(strict_types=1);

use Zenith\Application;
use Zenith\Container\ContainerInterface;

/** Create the framework application around an application-provided container. */
return static function (ContainerInterface $container): Application {
    return new Application($container);
};
