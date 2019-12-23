<?php

declare(strict_types=1);

namespace App\Infrastructure\RoadRunner;

use Spiral\RoadRunner\PSR7Client;
use Zend\HttpHandlerRunner\Emitter;
use Psr\Container\ContainerInterface;
use Zend\Expressive\ApplicationPipeline;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Response\ServerRequestErrorResponseGenerator;

class RoadRunnerRequestHandlerRunnerFactory
{
    public function __invoke(ContainerInterface $container): RoadRunnerRequestHandlerRunner
    {
        /** @psalm-suppress UndefinedClass */
        $pipeline = $container->get(ApplicationPipeline::class);

        return new RoadRunnerRequestHandlerRunner(
            $pipeline,
            $container->get(Emitter\EmitterInterface::class),
            $container->get(PSR7Client::class),
            $container->get(ServerRequestInterface::class),
            $container->get(ServerRequestErrorResponseGenerator::class),
        );
    }
}
