<?php

declare(strict_types=1);

namespace App\Domain;

use DateTimeImmutable;

interface AccessTokenGenerator
{
    public function generate(DateTimeImmutable $expiresIn): string;
}
