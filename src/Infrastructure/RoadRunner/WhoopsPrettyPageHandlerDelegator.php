<?php

declare(strict_types=1);

namespace App\Infrastructure\RoadRunner;

use Psr\Container\ContainerInterface;
use Whoops\Handler\PrettyPageHandler;

class WhoopsPrettyPageHandlerDelegator
{
    public function __invoke(ContainerInterface $container, string $serviceName, callable $callback): PrettyPageHandler
    {
        /** @var PrettyPageHandler $pageHandler */
        $pageHandler = $callback();
        $pageHandler->handleUnconditionally(true);
        return $pageHandler;
    }
}
