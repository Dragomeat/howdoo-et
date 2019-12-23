<?php

declare(strict_types=1);

namespace App\Domain;

use DateInterval;
use DateTimeImmutable;

class AccessToken
{
    public const TTL = 3600;

    private string $userId;

    private string $token;

    private DateTimeImmutable $expiresIn;

    public static function issueNew(string $userId, AccessTokenGenerator $tokenGenerator): self
    {
        $expiresIn = (new DateTimeImmutable())->add(
            new DateInterval(
                sprintf('PT%dM', self::TTL / 60)
            )
        );

        $token = $tokenGenerator->generate($expiresIn);

        return new self($userId, $token, $expiresIn);
    }

    public function __construct(string $userId, string $token, DateTimeImmutable $expiresIn)
    {
        $this->userId = $userId;
        $this->token = $token;
        $this->expiresIn = $expiresIn;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getExpiresIn(): DateTimeImmutable
    {
        return $this->expiresIn;
    }

    public function isExpired(): bool
    {
        return $this->expiresIn < new DateTimeImmutable();
    }
}
