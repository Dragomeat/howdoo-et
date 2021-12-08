<?php

declare(strict_types=1);

namespace App\Infrastructure\RoadRunner;

use Throwable;
use Spiral\RoadRunner\PSR7Client;
use Psr\Http\Message\ResponseInterface;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;

class RoadRunnerEmitter implements EmitterInterface
{
    private PSR7Client $psr7Client;

    public function __construct(PSR7Client $psr7Client)
    {
        $this->psr7Client = $psr7Client;
    }

    public function emit(ResponseInterface $response): bool
    {
        try {
            $this->psr7Client->respond($response);

            return true;
        } catch (Throwable $e) {
            return false;
        }
    }
}
