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

namespace GeckoPackages\DiffOutputBuilder\Tests;

use PHPUnit\Framework\TestCase;
use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\DiffOutputBuilderInterface;

/**
 * @author SpacePossum
 *
 * @coversNothing
 *
 * @internal
 */
abstract class AbstractDiffOutputBuilderTest extends TestCase
{
    /**
     * @var string
     */
    private $differClass;

    abstract public function provideOutputBuildingCases(): array;

    abstract public function provideSample(): array;

    abstract public function assertValidDiffFormat(string $diff);

    /**
     * @param string $expected
     * @param string $from
     * @param string $to
     * @param array  $options
     *
     * @dataProvider provideOutputBuildingCases
     */
    public function testOutputBuilding(string $expected, string $from, string $to, array $options)
    {
        $diff = $this->getDiffer($options)->diff($from, $to);

        $this->assertValidDiffFormat($diff);
        $this->assertSame($expected, $diff);
    }

    /**
     * @param string $expected
     * @param string $from
     * @param string $to
     * @param array  $options
     *
     * @dataProvider provideSample
     */
    public function testSample(string $expected, string $from, string $to, array $options)
    {
        $diff = $this->getDiffer($options)->diff($from, $to);

        $this->assertValidDiffFormat($diff);
        $this->assertSame($expected, $diff);
    }

    /**
     * Returns a new instance of a Differ with a new instance of the class (DiffOutputBuilderInterface) under test.
     *
     * @param array $options
     *
     * @return Differ
     */
    protected function getDiffer(array $options = []): Differ
    {
        if (null === $this->differClass) {
            // map test class name (child) back to the Differ being tested.
            $childClass = \get_class($this);
            $differClass = 'GeckoPackages\DiffOutputBuilder\\'.\substr($childClass, \strrpos($childClass, '\\') + 1, -4);

            // basic tests: class must exist...
            $this->assertTrue(\class_exists($differClass));

            // ...and must implement DiffOutputBuilderInterface
            $implements = \class_implements($differClass);
            $this->assertInternalType('array', $implements);
            $this->assertArrayHasKey(DiffOutputBuilderInterface::class, $implements);

            $this->differClass = $differClass;
        }

        return new Differ(new $this->differClass($options));
    }
}
