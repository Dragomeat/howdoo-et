<?php

declare(strict_types=1);

namespace App\Domain;

class AuthenticationService
{
    private AccessTokens $accessTokens;

    private AccessTokenGenerator $accessTokenGenerator;

    public function __construct(
        AccessTokens $accessTokens,
        AccessTokenGenerator $accessTokenGenerator
    ) {
        $this->accessTokens = $accessTokens;
        $this->accessTokenGenerator = $accessTokenGenerator;
    }

    public function authenticateByUserId(string $userId): AccessToken
    {
        $accessToken = AccessToken::issueNew($userId, $this->accessTokenGenerator);

        $this->accessTokens->add($accessToken);

        return $accessToken;
    }

    public function authenticateByToken(string $token): ?AccessToken
    {
        $accessToken = $this->accessTokens->find($token);

        if ($accessToken === null || $accessToken->isExpired()) {
            return null;
        }

        return $accessToken;
    }
}
