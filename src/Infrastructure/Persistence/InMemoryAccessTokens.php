<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\AccessToken;
use App\Domain\AccessTokens;
use Doctrine\Common\Collections\ArrayCollection;

class InMemoryAccessTokens implements AccessTokens
{
    /**
     * @var ArrayCollection<string, AccessToken>
     */
    private ArrayCollection $tokens;

    public function __construct()
    {
        $this->tokens = new ArrayCollection();
    }

    public function find(string $token): ?AccessToken
    {
        return $this->tokens->get($token);
    }

    public function add(AccessToken $accessToken): void
    {
        $this->tokens->set($accessToken->getToken(), $accessToken);
    }
}
