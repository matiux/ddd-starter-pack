<?php

declare(strict_types=1);

namespace DDDStarterPack\Tool;

class EnvVarUtil
{
    /**
     * @psalm-suppress PossiblyInvalidCast
     *
     * @param non-empty-string $name
     * @param string           $default
     *
     * @return string
     */
    public static function get(string $name, string $default = ''): string
    {
        return getenv($name) ?: (string) ($_ENV[$name] ?? $default);
    }
}
