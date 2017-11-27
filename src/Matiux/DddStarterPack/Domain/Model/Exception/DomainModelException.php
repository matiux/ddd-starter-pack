<?php

namespace DddStarterPack\Domain\Model\Exception;

use Throwable;

abstract class DomainModelException extends \Exception
{
    const MESSAGE = 'An error has occured';

    public function __construct(string $message = "", int $code = 500, Throwable $previous = null)
    {
        $message = $message ?: static::MESSAGE;

        parent::__construct($message, $code, $previous);
    }

    public function toArray()
    {
        $a['code'] = $this->getCode();
        $a['message'] = $this->getMessage();

        return $a;
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
}
