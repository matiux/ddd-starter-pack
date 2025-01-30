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
        $env = getenv($name);

        if (static::valid($env)) {
            return (string) $env;
        }

        $env = $_ENV[$name] ?? null;

        if (static::valid($env)) {
            return (string) $env;
        }

        return $default;
    }

    /**
     * @param non-empty-string $name
     *
     * @return null|string
     */
    public static function getOrNull(string $name): null|string
    {
        $value = static::get($name);

        return '' === $value ? null : $value;
    }

    private static function valid(mixed $val): bool
    {
        return \is_string($val) && strlen($val) > 0;
    }
}
