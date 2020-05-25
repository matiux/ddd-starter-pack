<?php

declare(strict_types=1);

namespace DDDStarterPack\Domain\Aggregate;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

class BasicEntityId implements EntityId
{
    const UUID_PATTERN = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/';

    /** @var null|int|string */
    private $id;

    /**
     * @param null|int|string $id
     */
    protected function __construct($id)
    {
        $this->verifyInputId($id);

        $this->id = $id;
    }

    public function __toString(): string
    {
        return !$this->id ? '' : (string) $this->id;
    }

    /**
     * @psalm-suppress DocblockTypeContradiction
     *
     * @param mixed $anId
     */
    private function verifyInputId($anId): void
    {
        if (is_object($anId)) {
            throw new InvalidArgumentException('Entity id input must be scalar type');
        }
    }

    /**
     * @throws \Exception
     *
     * @return static
     */
    public static function create(): EntityId
    {
        return new static(Uuid::uuid4()->toString());
    }

    public static function createFrom($id): EntityId
    {
        if (!$id) {
            throw new InvalidArgumentException(sprintf('Invalid ID: %s', $id));
        }

        return new static($id);
    }

    public static function createNUll(): EntityId
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

    public function equals($entityId): bool
    {
        return $this->id() === $entityId->id();
    }

    public function id()
    {
        return $this->id;
    }

    public function isNull(): bool
    {
        return is_null($this->id);
    }
}
