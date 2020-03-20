<?php

namespace DDDStarterPack\Domain\Service;

class BasicServiceResponse implements ServiceResponse
{
    private $success;
    private $message;
    private $code;

    private function __construct()
    {
    }

    public static function error(): self
    {
        $response = new self;
        $response->success = false;

        return $response;
    }

    public static function success(): self
    {
        $response = new self;
        $response->success = true;

        return $response;
    }

    public function withMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function withCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function code(): int
    {
        return $this->code;
    }
}
