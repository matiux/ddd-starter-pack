<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Domain;

use Exception;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

/**
 * @extends  BasicEntityId<string>
 */
class UuidEntityId extends BasicEntityId
{
    public const UUID_PATTERN = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/';

    public function __toString(): string
    {
        return !$this->id ? '' : $this->id;
    }

    /**
     * @throws Exception
     *
     * @return static
     */
    public static function create(): static
    {
        return new static(Uuid::uuid4()->toString());
    }

    /**
     * @param string $id
     *
     * @return static
     */
    public static function createFrom($id): static
    {
        if (!$id || !self::isValidUuid($id)) {
            throw new InvalidArgumentException(sprintf('Invalid ID: %s', $id));
        }

        return new static($id);
    }

    public static function isValidUuid(string $uuid): bool
    {
        if (1 === preg_match(self::UUID_PATTERN, $uuid)) {
            return true;
        }

        return false;
    }
}
