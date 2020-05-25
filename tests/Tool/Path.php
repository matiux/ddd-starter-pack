<?php

declare(strict_types=1);

namespace Tests\Tool;

class Path
{
    public static function test(): string
    {
        return realpath(__DIR__.'/..');
    }
}
