<?php

declare(strict_types=1);

namespace DDDStarterPack\Exception;

/**
 * @codeCoverageIgnore
 */
abstract class DomainException extends \Exception
{
    /** @var string */
    public const MESSAGE = 'An error has occurred';
    protected array $context = [];

    final public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        $message = $message ?: static::MESSAGE;

        parent::__construct($message, $code, $previous);
    }

    public static function withContext(string $message, array $context): static
    {
        $e = new static($message);

        $e->context = $context;

        return $e;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function toArray(): array
    {
        return [
            'code' => $this->getCode(),
            'message' => $this->getMessage(),
        ];
    }

    protected static function obtainMessage(null|\Throwable $previous = null): string
    {
        $msg = '';

        if (!$previous) {
            return $msg;
        }

        $msg = $previous->getMessage();

        if (empty($msg)) {
            $msg = $previous->getPrevious()?->getMessage() ?? '';
        }

        return $msg;
    }
}
