<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Tool;

use DDDStarterPack\Tool\EnvVarUtil;
use PHPUnit\Framework\TestCase;

class EnvVarUtilTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_return_null(): void
    {
        $val = EnvVarUtil::getOrNull('FOO');

        self::assertNull($val);
    }

    /**
     * @test
     */
    public function it_should_return_value(): void
    {
        $_ENV['FOO'] = 'value';

        $val = EnvVarUtil::getOrNull('FOO');

        self::assertEquals('value', $val);
    }
}
