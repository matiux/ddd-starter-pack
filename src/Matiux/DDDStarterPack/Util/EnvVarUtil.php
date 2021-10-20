<?php

declare(strict_types=1);

namespace DDDStarterPack\Util;

class EnvVarUtil
{
    public static function get(string $name, string $default = ''): string
    {
        return getenv($name) ?: (string) ($_ENV[$name] ?? $default);
    }
}
