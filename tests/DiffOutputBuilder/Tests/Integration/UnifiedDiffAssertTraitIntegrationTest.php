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

use GeckoPackages\DiffOutputBuilder\Utils\UnifiedDiffAssertTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

/**
 * @author SpacePossum
 *
 * @requires OS Linux
 *
 * @coversNothing
 *
 * @internal
 */
final class UnifiedDiffAssertTraitIntegrationTest extends TestCase
{
    use UnifiedDiffAssertTrait;

    private $filePatch;

    protected function setUp()
    {
        $this->filePatch = __DIR__.'/out/patch.txt';

        $this->cleanUpTempFiles();
    }

    /**
     * @param string $fileFrom
     * @param string $fileTo
     *
     * @dataProvider provideFilePairsCases
     */
    public function testValidPatches(string $fileFrom, string $fileTo)
    {
        $command = \sprintf(
            'diff -u %s %s > %s',
            \escapeshellarg(\realpath($fileFrom)),
            \escapeshellarg(\realpath($fileTo)),
            \escapeshellarg($this->filePatch)
        );

        $p = new Process($command);
        $p->run();

        $exitCode = $p->getExitCode();

        if (0 === $exitCode) {
            // odd case when two files have the same content. Test after executing as it is more efficient than to read the files and check the contents every time.
            $this->addToAssertionCount(1);

            return;
        }

        $this->assertSame(
            1, // means `diff` found a diff between the files we gave it
            $exitCode,
            \sprintf(
                "Command exec. was not successful:\n\"%s\"\nOutput:\n\"%s\"\nStdErr:\n\"%s\"\nExit code %d.\n",
                $command,
                $p->getOutput(),
                $p->getErrorOutput(),
                $p->getExitCode()
            )
        );

        $this->assertValidUnifiedDiffFormat(\file_get_contents($this->filePatch));
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function provideFilePairsCases(): array
    {
        $cases = [];

        // created cases based on dedicated fixtures
        $dir = \realpath(__DIR__.'/fixtures/UnifiedDiffAssertTraitIntegrationTest');
        $dirLength = \strlen($dir);

        for ($i = 1;; ++$i) {
            $fromFile = \sprintf('%s/%d_a.txt', $dir, $i);
            $toFile = \sprintf('%s/%d_b.txt', $dir, $i);

            if (!\file_exists($fromFile)) {
                break;
            }

            $this->assertFileExists($toFile);
            $cases[\sprintf("Diff file:\n\"%s\"\nvs.\n\"%s\"\n", \substr(\realpath($fromFile), $dirLength), \substr(\realpath($toFile), $dirLength))] = [$fromFile, $toFile];
        }

        // create cases based on PHP files within the vendor directory for integration testing
        $dir = \realpath(__DIR__.'/../../../../vendor');
        $dirLength = \strlen($dir);

        $fileIterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS));
        $fromFile = __FILE__;

        /** @var \SplFileInfo $file */
        foreach ($fileIterator as $file) {
            if ('php' !== $file->getExtension()) {
                continue;
            }

            $toFile = $file->getPathname();
            $cases[\sprintf("Diff file:\n\"%s\"\nvs.\n\"%s\"\n", \substr(\realpath($fromFile), $dirLength), \substr(\realpath($toFile), $dirLength))] = [$fromFile, $toFile];
            $fromFile = $toFile;
        }

        return $cases;
    }

    protected function tearDown()
    {
        $this->cleanUpTempFiles();
    }

    private function cleanUpTempFiles()
    {
        @\unlink($this->filePatch);
    }
}
