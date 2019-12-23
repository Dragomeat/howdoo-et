<?php

declare(strict_types=1);

namespace App\Domain;

interface AccessTokens
{
    public function find(string $token): ?AccessToken;

    public function add(AccessToken $accessToken): void;
}
