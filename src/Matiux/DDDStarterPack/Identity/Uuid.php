<?php

declare(strict_types=1);

namespace DDDStarterPack\Identity;

/**
 * @extends  BasicGenericId<string>
 */
abstract readonly class Uuid extends BasicGenericId
{
    // public const UUID_PATTERN = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/';
    // See https://github.com/ramsey/uuid/blob/4.x/src/Validator/GenericValidator.php#L32
    public const UUID_PATTERN = '/\A[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}\z/';

    public function __toString(): string
    {
        return empty($this->value()) ? '' : $this->value();
    }

    /**
     * @throws \Exception
     *
     * @return static
     */
    abstract public static function new(): static;

    /**
     * @param string $id
     *
     * @throws \InvalidArgumentException
     */
    public static function from($id): static
    {
        if (!$id || !self::isValidUuid($id)) {
            throw new \InvalidArgumentException(sprintf('Invalid ID: %s', $id));
        }

        return new static($id);
    }

    /**
     * @param null|string $id
     *
     * @return null|static
     */
    public static function tryFrom($id): null|static
    {
        if (is_null($id)) {
            return null;
        }

        try {
            return self::from($id);
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function isValidUuid(string $uuid): bool
    {
        if (1 === preg_match(self::UUID_PATTERN, $uuid)) {
            return true;
        }

        return false;
    }
}
