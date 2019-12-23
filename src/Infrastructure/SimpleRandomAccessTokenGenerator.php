<?php

declare(strict_types=1);

namespace App\Infrastructure;

use DateTimeImmutable;
use App\Domain\AccessTokenGenerator;

class SimpleRandomAccessTokenGenerator implements AccessTokenGenerator
{
    public function generate(DateTimeImmutable $expiresIn): string
    {
        return bin2hex(random_bytes(16));
    }
}
