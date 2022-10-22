<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Domain\Identifier;

use Exception;
use InvalidArgumentException;

/**
 * @extends  BasicEntityId<string>
 */
abstract class UuidEntityId extends BasicEntityId
{
    // public const UUID_PATTERN = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/';
    // See https://github.com/ramsey/uuid/blob/4.x/src/Validator/GenericValidator.php#L32
    public const UUID_PATTERN = '/\A[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}\z/';

    public function __toString(): string
    {
        return empty($this->value()) ? '' : $this->value();
    }

    /**
     * @throws Exception
     *
     * @return static
     */
    abstract public static function create(): static;

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
