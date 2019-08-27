<?php

namespace DDDStarterPack\Application\Service;

abstract class BasicApplicationServiceResponse implements ApplicationServiceResponse
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

    public function getResponse(): array
    {
        return $this->response;
    }
}
