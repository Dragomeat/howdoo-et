<?php

declare(strict_types=1);

namespace App\Tests\Domain;

use DateTimeImmutable;
use App\Domain\AccessToken;
use PHPUnit\Framework\TestCase;
use App\Domain\AuthenticationService;
use App\Infrastructure\Persistence\InMemoryAccessTokens;
use App\Infrastructure\SimpleRandomAccessTokenGenerator;

class AuthenticationServiceTest extends TestCase
{
    private AuthenticationService $authenticationService;

    protected function setUp()
    {
        $accessTokens = new InMemoryAccessTokens();

        foreach ([
                new AccessToken('1', 'token1', new DateTimeImmutable('+1 day')),
                new AccessToken('2', 'token2', new DateTimeImmutable('+1 hour')),
                new AccessToken('2', 'token3', new DateTimeImmutable('+1 hour')),
                new AccessToken('3', 'token4', new DateTimeImmutable('-1 day')),
        ] as $accessToken) {
            $accessTokens->add($accessToken);
        }

        $this->authenticationService = new AuthenticationService(
            $accessTokens,
            new SimpleRandomAccessTokenGenerator()
        );
    }

    public function testAuthenticationByUserId(): void
    {
        $userId = '24324343';

        $accessToken = $this->authenticationService->authenticateByUserId($userId);

        $this->assertEquals($userId, $accessToken->getUserId());
        $this->assertFalse($accessToken->isExpired());
    }

    /**
     * @dataProvider tokenProvider
     *
     * @param string $tokenId
     * @param string $userId
     * @param bool $isExpired
     */
    public function testAuthenticationByToken(string $tokenId, string $userId, bool $isExpired): void
    {
        $accessToken = $this->authenticationService->authenticateByToken($tokenId);

        if ($isExpired) {
            $this->assertNull($accessToken);
        } else {
            $this->assertNotNull($accessToken);
            $this->assertEquals($userId, $accessToken->getUserId());
        }
    }

    public function tokenProvider(): array
    {
        return [
            ['token1', '1', false],
            ['token2', '2', false],
            ['token4', '3', true],
        ];
    }
}
