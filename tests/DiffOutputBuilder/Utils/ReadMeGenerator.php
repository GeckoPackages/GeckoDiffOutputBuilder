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

namespace GeckoPackages\DiffOutputBuilder\Utils;

/*
 * @author SpacePossum
 *
 * @internal
 */
use GeckoPackages\DiffOutputBuilder\Tests\AbstractDiffOutputBuilderTest;
use PhpCsFixer\DocBlock\DocBlock;
use SebastianBergmann\Diff\Output\DiffOutputBuilderInterface;

final class ReadMeGenerator
{
    private $template = <<<'TEMPLATE'
#### GeckoPackages

# Diff output extensions

Pure PHP strict Unified diff format output builder (similar to `diff -u`).

### Requirements

- PHP 7
- `sebastian/diff` `^2.0`

This package is framework agnostic, meaning that you can use it in any PHP project.

### Install

The package can be installed using Composer:

```
composer require gecko-packages/gecko-diff-output-builder
```

### Usage

%%%OUTPUT_BUILDERS_COPY%%%

### Links

- [Get Composer](https://getcomposer.org/)
- [Sebastian Bergmann Diff repository](https://github.com/sebastianbergmann/diff)
- [Unified diff format description](https://www.gnu.org/software/diffutils/manual/html_node/Unified-Format.html)

### License

The project is released under the MIT license, see the LICENSE file.

### Contributions

Contributions are welcome!<br/>
Visit us on [github :octocat:](https://github.com/GeckoPackages/GeckoDiffOutputBuilder)

### Semantic Versioning

This project follows [Semantic Versioning](http://semver.org/).

<sub>Kindly note:
We do not keep a backwards compatible promise on code annotated with `@internal`, the tests and tooling (such as document generation) of the project itself
nor the content and/or format of exception/error messages.</sub>

This project is maintained on [github :octocat:](https://github.com/GeckoPackages/GeckoDiffOutputBuilder). Visit us! :)

TEMPLATE
;

    /**
     * @return string
     */
    public function generateReadMe(): string
    {
        $outputBuildersCopy = '';
        foreach ($this->getOutputBuilderClasses() as $class) {
            $outputBuildersCopy .= $this->generateDocForOutputBuilder($class);
        }

        return \str_replace('%%%OUTPUT_BUILDERS_COPY%%%', $outputBuildersCopy, $this->template);
    }

    public function getReadMeFile(): string
    {
        return __DIR__.'/../../../README.md';
    }

    private function generateDocForOutputBuilder(\ReflectionClass $reflection): string
    {
        $doc = $reflection->getDocComment();
        if (false === $doc) {
            throw new \UnexpectedValueException(\sprintf('Missing doc comment for "%s".', $reflection->getName()));
        }

        $name = $description = $options = '';

        foreach ((new DocBlock($doc))->getAnnotations() as $annotation) {
            switch ($annotation->getTag()->getName()) {
                case 'name':
                    $name = \rtrim(\substr($annotation->getContent(), 9));

                    break;
                case 'description':
                    $description = \rtrim(\substr($annotation->getContent(), 16));

                    break;
            }
        }

        $testClass = 'GeckoPackages\DiffOutputBuilder\Tests\\'.$reflection->getShortName().'Test';
        if (!\class_exists($testClass)) {
            throw new \LogicException(\sprintf('Missing test class "%s".', $testClass));
        }

        /** @var AbstractDiffOutputBuilderTest $test */
        $test = new $testClass();
        $sample = $test->provideSample();
        $sample = \reset($sample);
        $diff = $sample[0];

        $sampleOptions = "        [\n";
        foreach ($sample[3] as $key => $value) {
            $sampleOptions .= "            '".$key."' => ".(\is_string($value) ? "'".$value."'" : $value).",\n";
        }
        $sampleOptions .= '        ]';

        $sample = \sprintf(
            '<?php
use SebastianBergmann\Diff\Differ;
use %s;

$differ = new Differ(
    new %s(
%s
    )
);

$from = \'%s\';

$to = \'%s\';

$diff = $differ->diff($from, $to);
',
            $reflection->getName(),
            $reflection->getShortName(),
            $sampleOptions,
            $sample[1],
            $sample[2]
        );

        foreach ($reflection->getStaticProperties()['default'] as $option => $default) {
            if (null === $default) {
                $default = '<null>';
            } elseif (false === $default) {
                $default = 'false';
            } elseif (true === $default) {
                $default = 'true';
            }

            $options .= \sprintf("- `%s`, default: `%s`.\n", $option, $default);
        }

        return
            '## '.$name.
            "\n\n###### ".$reflection->getName().
            "\n\n".$description.
            "\n\nExample:\n```php\n".$sample."\n```\n".
            "\nDiff:\n```diff\n".$diff."```\n".
            "\nOptions:\n".$options
        ;
    }

    /**
     * @return \ReflectionClass
     */
    private function getOutputBuilderClasses()
    {
        $namespace = 'GeckoPackages\DiffOutputBuilder\\';
        $sourceFolder = __DIR__.'/../../../src/DiffOutputBuilder';

        foreach (new \DirectoryIterator($sourceFolder) as $candidate) {
            if (!$candidate->isFile() || 'php' !== $candidate->getExtension()) {
                continue;
            }

            $candidate = $namespace.\substr($candidate->getFilename(), 0, -4);
            if (!\class_exists($candidate)) {
                continue;
            }

            $reflection = new \ReflectionClass($candidate);
            if (!\in_array(DiffOutputBuilderInterface::class, $reflection->getInterfaceNames(), true)) {
                continue;
            }

            yield $reflection;
        }
    }
}
