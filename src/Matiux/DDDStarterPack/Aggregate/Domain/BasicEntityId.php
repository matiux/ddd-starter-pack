<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Domain;

use Exception;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

class BasicEntityId implements EntityId
{
    public const UUID_PATTERN = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/';

    private null|int|string $id;

    final protected function __construct(null|int|string $id)
    {
        $this->verifyInputId($id);

        $this->id = $id;
    }

    public function __toString(): string
    {
        return !$this->id ? '' : (string) $this->id;
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

    public static function createFrom(int|string $id): static
    {
        if (!$id) {
            throw new InvalidArgumentException(sprintf('Invalid ID: %s', $id));
        }

        return new static($id);
    }

    public static function createNUll(): static
    {
        return new static(null);
    }

    public static function isValidUuid(string $uuid): bool
    {
        if (1 === preg_match(self::UUID_PATTERN, $uuid)) {
            return true;
        }

        return false;
    }

    public function equals(EntityId $entityId): bool
    {
        return $this->id() === $entityId->id();
    }

    public function id(): null|int|string
    {
        return $this->id;
    }

    public function isNull(): bool
    {
        return is_null($this->id);
    }

    /**
     * @param mixed $anId
     */
    private function verifyInputId($anId): void
    {
        if (is_object($anId)) {
            throw new InvalidArgumentException('Entity id input must be scalar type');
        }
    }
}
