<?php

namespace DddStarterPack\Domain\Model\Exception;

use Throwable;

abstract class DomainModelNotFoundException extends \Exception
{
    const message = 'Model not found';

    public function __construct($message = "", $code = 404, Throwable $previous = null)
    {
        $message = $message ?: static::message;

        parent::__construct($message, $code, $previous);
    }

    public static function notFoundMessage($modelId)
    {
        return static::message . " [$modelId]";
    }
}
