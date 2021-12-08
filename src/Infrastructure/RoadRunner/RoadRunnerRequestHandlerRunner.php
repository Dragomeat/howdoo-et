<?php

declare(strict_types=1);

namespace App\Infrastructure\RoadRunner;

use Spiral\RoadRunner\PSR7Client;
use Laminas\HttpHandlerRunner\Emitter;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\HttpHandlerRunner\RequestHandlerRunner;

class RoadRunnerRequestHandlerRunner extends RequestHandlerRunner
{
    private RequestHandlerInterface $handler;

    private Emitter\EmitterInterface $emitter;

    private PSR7Client $psr7Client;

    public function __construct(
        RequestHandlerInterface $handler,
        Emitter\EmitterInterface $emitter,
        PSR7Client $psr7Client,
        callable $serverRequestFactory,
        callable $serverRequestErrorResponseGenerator
    ) {
        parent::__construct(
            $handler,
            $emitter,
            $serverRequestFactory,
            $serverRequestErrorResponseGenerator
        );

        $this->handler = $handler;
        $this->emitter = $emitter;
        $this->psr7Client = $psr7Client;
    }

    public function run(): void
    {
        while ($request = $this->psr7Client->acceptRequest()) {
            $response = $this->handler->handle($request);

            $this->emitter->emit($response);
        }
    }
}
