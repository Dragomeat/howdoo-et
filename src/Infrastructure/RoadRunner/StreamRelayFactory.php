<?php

declare(strict_types=1);

namespace App\Infrastructure\RoadRunner;

use Spiral\Goridge\StreamRelay;

class StreamRelayFactory
{
    public function __invoke(): StreamRelay
    {
        return new StreamRelay(STDIN, STDOUT);
    }
}
