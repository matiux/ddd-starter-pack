<?php

namespace DddStarterPack\Domain\Model\Exception;

use Throwable;

abstract class DomainModelException extends \Exception
{
    const message = 'An error has occured';

    public function __construct($message = "", $code = 500, Throwable $previous = null)
    {
        $message = $message ?: static::message;

        parent::__construct($message, $code, $previous);
    }

    public static function errorMessage(string $details, bool $append = true): string
    {
        $message =
            $append
                ? static::message . " [$details]"
                : $details;

        return $message;
    }
}
