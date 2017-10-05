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

use GeckoPackages\DiffOutputBuilder\Utils\ReadMeGenerator;
use PHPUnit\Framework\TestCase;

/**
 * @author SpacePossum
 *
 * @covers \GeckoPackages\DiffOutputBuilder\Utils\ReadMeGenerator
 *
 * @internal
 */
final class ReadMeTest extends TestCase
{
    public function testReadme()
    {
        $generator = new ReadMeGenerator();
        $readmeFile = $generator->getReadMeFile();

        $this->assertFileExists($readmeFile, 'Missing README.md, please run `$ ./bin/generate_readme` from the root of the project.');
        $this->assertStringEqualsFile(
            $readmeFile,
            $generator->generateReadMe(),
            'README.md is out of sync, please run `$ ./bin/generate_readme` from the root of the project.'
        );
    }
}
