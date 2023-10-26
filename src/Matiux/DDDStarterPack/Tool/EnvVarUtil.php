<?php

declare(strict_types=1);

namespace DDDStarterPack\Tool;

class EnvVarUtil
{
    /**
     * @param non-empty-string $name
     * @param string           $default
     *
     * @return string
     */
    public static function get(string $name, string $default = ''): string
    {
        return getenv($name) ?: (string) ($_ENV[$name] ?? $default);
    }

    /**
     * @param non-empty-string $name
     *
     * @return null|string
     */
    public static function getOrNull(string $name): null|string
    {
        $value = getenv($name) ?: $_ENV[$name] ?? null;

        return !$value ? null : (string) $value;
    }
}
