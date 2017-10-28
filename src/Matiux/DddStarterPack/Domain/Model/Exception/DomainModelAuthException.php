<?php

namespace DddStarterPack\Domain\Model\Exception;

use Throwable;

abstract class DomainModelAuthException extends \Exception
{
    const message = 'Authorization problem';

    public function __construct($message = "", $code = 403, Throwable $previous = null)
    {
        $message = $message ?: static::message;

        parent::__construct($message, $code, $previous);
    }

    public static function errorMessage($message)
    {
        return static::message . " [$message]";
    }
}
