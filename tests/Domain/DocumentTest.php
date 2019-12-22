<?php

declare(strict_types=1);

namespace App\Tests\Domain;

use DateTimeImmutable;
use App\Domain\Document;
use PHPUnit\Framework\TestCase;
use App\Domain\DocumentCannotBeEdited;
use function Ramsey\Uuid\v4 as uuid;

class DocumentTest extends TestCase
{
    private function createDocument(string $status = Document::STATUS_DRAFT): Document
    {
        $id = uuid();
        $createdAt = new DateTimeImmutable();

        return new Document($id, $status, [], $createdAt);
    }

    public function testDraftCanBeCreated(): void
    {
        $document = $this->createDocument();

        $this->assertEquals(Document::STATUS_DRAFT, $document->getStatus());
    }

    /**
     * @dataProvider payloadDataProvider
     * @param array $payload
     */
    public function testDraftCanBeEdited(array $payload): void
    {
        $document = $this->createDocument();

        $document->edit($payload);

        $this->assertEquals($payload, $document->getPayload());
    }

    public function testDraftCanBePublished(): void
    {
        $document = $this->createDocument();

        $document->publish();

        $this->assertEquals(Document::STATUS_PUBLISHED, $document->getStatus());
    }

    /**
     * @dataProvider payloadDataProvider
     * @param array $payload
     */
    public function testPublishedCannotBeEdited(array $payload): void
    {
        $this->expectException(DocumentCannotBeEdited::class);

        $document = $this->createDocument(Document::STATUS_PUBLISHED);

        $document->edit($payload);
    }

    public function payloadDataProvider(): array
    {
        return [
            [
                [
                    'actor' => 'The fox',
                    'meta' => ['type' => 'cunning', 'color' => 'brown'],
                    'actions' => [
                        ['action' => 'jump over', 'actor' => 'lazy dog'],
                    ]
                ],
            ],
            [
                [
                    'actor' => 'The fox',
                    'meta' => ['type' => 'cunning'],
                    'actions' => [
                        ['action' => 'eat', 'actor' => 'blob'],
                        ['action' => 'run away'],
                    ],
                ],
            ],
        ];
    }
}
