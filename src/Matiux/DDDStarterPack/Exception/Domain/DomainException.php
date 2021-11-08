<?php

declare(strict_types=1);

namespace DDDStarterPack\Exception\Domain;

use Exception;
use Throwable;

/**
 * @codeCoverageIgnore
 */
abstract class DomainException extends Exception
{
    public const MESSAGE = 'An error has occurred';

    public function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        $message = $message ?: (string) static::MESSAGE;

        parent::__construct($message, $code, $previous);
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

    protected static function obtainMessage(null|Throwable $previous = null): string
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
