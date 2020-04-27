<?php

namespace DDDStarterPack\Application\Util;

class EnvVarUtil
{
    public static function get(string $name, string $default = null): ?string
    {
        return getenv($name) ?: $_ENV[$name] ?? $default;
    }
}
