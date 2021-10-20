<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Domain;

use DateTime;

trait LoggingInfo
{
    protected null|DateTime $created = null;
    protected null|DateTime $updated = null;
    protected null|string $editedBy = null;

    public function getUpdatingDate(): ?DateTime
    {
        return $this->updated;
    }

    public function getEditedBy(): ?string
    {
        return $this->editedBy;
    }

    public function setEditor(string $editor): void
    {
        $this->editedBy = $editor;
    }

    public function getCreatedDate(): ?DateTime
    {
        return $this->created;
    }
}
