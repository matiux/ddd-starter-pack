<?php

namespace DddStarterPack\Application\Exception;

use Throwable;

abstract class ApplicationException extends \Exception
{
    const message = 'An error was occured';

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
