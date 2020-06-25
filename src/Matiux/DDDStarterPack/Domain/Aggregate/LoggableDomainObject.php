<?php

declare(strict_types=1);

namespace DDDStarterPack\Domain\Aggregate;

use DateTimeImmutable;

interface LoggableDomainObject
{
    public function getUpdatingDate(): ?DateTimeImmutable;

    public function getEditedBy(): ?string;

    public function setEditor(string $editor): void;

    public function getCreatedDate(): ?DateTimeImmutable;
}
