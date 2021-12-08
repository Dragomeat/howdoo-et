<?php

declare(strict_types=1);

namespace App\Infrastructure\RoadRunner;

use Spiral\RoadRunner\Worker;
use Spiral\Goridge\StreamRelay;
use Spiral\RoadRunner\PSR7Client;
use Spiral\Goridge\RelayInterface;
use Laminas\HttpHandlerRunner\RequestHandlerRunner;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;

class Provider
{
    public function __invoke()
    {
        return [
            'dependencies' => [
                'factories' => [
                    Worker::class => ReflectionBasedAbstractFactory::class,
                    PSR7Client::class => ReflectionBasedAbstractFactory::class,
                    StreamRelay::class => StreamRelayFactory::class,
                    RoadRunnerEmitter::class => ReflectionBasedAbstractFactory::class,
                    RoadRunnerRequestHandlerRunner::class => RoadRunnerRequestHandlerRunnerFactory::class,
                ],
                'aliases' => [
                    RelayInterface::class => StreamRelay::class,
                    EmitterInterface::class => RoadRunnerEmitter::class,
                    RequestHandlerRunner::class => RoadRunnerRequestHandlerRunner::class,
                ],
                'delegators' => [
                    'Mezzio\WhoopsPageHandler' => [
                        WhoopsPrettyPageHandlerDelegator::class,
                    ],
                ],
            ],
        ];
    }
}
