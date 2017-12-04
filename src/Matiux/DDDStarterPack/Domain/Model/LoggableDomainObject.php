<?php

namespace DDDStarterPack\Domain\Model;

interface LoggableDomainObject
{
    public function getUpdatingDate(): ?\DateTime;

    public function getEditedBy(): ?string;

    public function setEditor(string $editor);

    public function getCreatedDate(): ?\DateTime;
}
