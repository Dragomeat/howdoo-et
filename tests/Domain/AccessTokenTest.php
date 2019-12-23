<?php

declare(strict_types=1);

namespace App\Tests\Domain;

use App\Domain\AccessToken;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class AccessTokenTest extends TestCase
{
    public function testAccessTokenNotExpiredBeforeExpiresDate(): void
    {
        $now = new DateTimeImmutable();
        $expiresIn = $now->modify('+1 day');

        $token = new AccessToken('15', '42', $expiresIn);

        $this->assertFalse($token->isExpired());
    }

    public function testAccessTokenExpiredAfterExpiresDate(): void
    {
        $now = new DateTimeImmutable();
        $expiresIn = $now->modify('-1 day');

        $token = new AccessToken('15', '42', $expiresIn);

        $this->assertTrue($token->isExpired());
    }
}
