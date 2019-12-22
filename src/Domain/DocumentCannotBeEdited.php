<?php

declare(strict_types=1);

namespace App\Domain;

use Throwable;
use DomainException;

class DocumentCannotBeEdited extends DomainException
{
    public static function alreadyPublished(string $id): self
    {
        return new self(
            sprintf('Document#%s cannot be edited, because it has already been published', $id)
        );
    }

    public function __construct(
        string $message = 'Document cannot be edited.',
        Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }
}
