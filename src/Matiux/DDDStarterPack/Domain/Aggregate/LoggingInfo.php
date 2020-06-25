<?php

declare(strict_types=1);

namespace DDDStarterPack\Domain\Aggregate;

use DateTime;

trait LoggingInfo
{
    protected $created;
    protected $updated;
    protected $editedBy;

    public function getUpdatingDate(): ?DateTime
    {
        return $this->updated;
    }

    public function getEditedBy(): ?string
    {
        return $this->editedBy;
    }

    public function setEditor(string $editor)
    {
        $this->editedBy = $editor;
    }

    public function getCreatedDate(): ?DateTime
    {
        return $this->created;
    }
}
