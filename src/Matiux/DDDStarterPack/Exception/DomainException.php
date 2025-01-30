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

    final public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null)
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
        return ($res = json_encode($this->toArray())) === false
            ? throw new \LogicException('toJson must return a string')
            : $res;
    }

    public function toArray(): array
    {
        return [
            'code' => $this->getCode(),
            'message' => $this->getMessage(),
        ];
    }

    public function enrichContext(array $context): void
    {
        $this->context = array_merge($this->context, $context);
    }

    protected static function obtainMessage(?\Throwable $previous = null): string
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
