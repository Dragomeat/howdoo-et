<?php

declare(strict_types=1);

namespace App\Domain;

use DateTimeImmutable;
use Webmozart\Assert\Assert;

class Document
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';

    private const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_PUBLISHED,
    ];

    private string $id;

    private string $status;

    private array $payload;

    private DateTimeImmutable $updatedAt;

    private DateTimeImmutable $createdAt;

    public function __construct(
        string $id,
        string $status,
        array $payload,
        DateTimeImmutable $createdAt
    ) {
        Assert::oneOf($status, self::STATUSES);

        $this->id = $id;
        $this->status = $status;
        $this->payload = $payload;
        $this->updatedAt = $this->createdAt = $createdAt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function edit(array $payload): void
    {
        $this->payload = $payload;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function publish(): void
    {
        $this->status = self::STATUS_PUBLISHED;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
