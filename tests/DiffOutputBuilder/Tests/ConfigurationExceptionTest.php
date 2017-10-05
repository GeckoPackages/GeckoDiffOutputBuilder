<?php

declare(strict_types=1);

/*
 * This file is part of the GeckoPackages.
 *
 * (c) GeckoPackages https://github.com/GeckoPackages
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace GeckoPackages\DiffOutputBuilder\Tests\ReadMe;

use GeckoPackages\DiffOutputBuilder\ConfigurationException;
use PHPUnit\Framework\TestCase;

/**
 * @author SpacePossum
 *
 * @covers \GeckoPackages\DiffOutputBuilder\ConfigurationException
 *
 * @internal
 */
final class ConfigurationExceptionTest extends TestCase
{
    public function testConstruct()
    {
        $e = new ConfigurationException('test', 'A', 'B');

        $this->assertSame(0, $e->getCode());
        $this->assertNull($e->getPrevious());
        $this->assertSame('Option "test" must be A, got "string#B".', $e->getMessage());
    }
}
