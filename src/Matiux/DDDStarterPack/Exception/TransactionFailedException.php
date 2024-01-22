<?php

declare(strict_types=1);

namespace DDDStarterPack\Exception;

use function Symfony\Component\String\u;

class TransactionFailedException extends ApplicationException
{
    public static function fromOriginalException(\Throwable $originalException, array $context = []): static
    {
        $e = new static(
            $originalException->getMessage(),
            intval($originalException->getCode()),
            $originalException,
        );

        $context['original_exception_name'] = u($originalException::class)->afterLast('\\')->toString();

        $e->context = $context;

        return $e;
    }
}
