<?php

declare(strict_types=1);

namespace DDDStarterPack\Repository\Test;

use PHPUnit\Framework\TestCase;

class DoctrineUtil
{
    public static function assertDQLEquals(string $expected, string $actual): void
    {
        $clear = static function (string $dql): string {
            $dql = strtr(trim($dql), [
                "\n" => '',
            ]);

            return preg_replace('/[\s]+/', ' ', $dql) ?? throw new \LogicException('DQL is empty');
        };
        $expected = $clear($expected);
        $actual = $clear($actual);
        TestCase::assertEquals($expected, $actual);
    }
}
