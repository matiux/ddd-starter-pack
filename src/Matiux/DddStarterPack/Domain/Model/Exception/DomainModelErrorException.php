<?php

namespace DddStarterPack\Domain\Model\Exception;

use Throwable;

abstract class DomainModelErrorException extends \Exception
{
    const message = 'An error has occured';

    public function __construct($message = "", $code = 500, Throwable $previous = null)
    {
        $message = $message ?: static::message;

        parent::__construct($message, $code, $previous);
    }

    public static function errorMessage($message)
    {
        return static::message . " [$message]";
    }
}
