<?php

namespace DDDStarterPack\Domain\Service;

class BasicServiceResponse implements ServiceResponse
{
    private $succes;
    private $response;

    public function __construct(bool $succes, array $response)
    {
        $this->succes = $succes;
        $this->response = $response;
    }

    public function getSuccess(): bool
    {
        return $this->succes;
    }

    public function getResponse()
    {
        return $this->response;
    }
}
