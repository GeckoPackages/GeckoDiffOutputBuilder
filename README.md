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

## Unified diff output builder

###### GeckoPackages\DiffOutputBuilder\UnifiedDiffOutputBuilder

Generates (strict) Unified diff's (unidiffs) with hunks.

Example:
```php
<?php
use SebastianBergmann\Diff\Differ;
use GeckoPackages\DiffOutputBuilder\UnifiedDiffOutputBuilder;

$differ = new Differ(
    new UnifiedDiffOutputBuilder(
        [
            'fromFile' => 'input.txt',
            'toFile' => 'output.txt',
        ]
    )
);

$from = '1
2
3
4
5
6
';

$to = '1
2
3
X
5
6
';

$diff = $differ->diff($from, $to);

```

Diff:
```diff
--- input.txt
+++ output.txt
@@ -1,6 +1,6 @@
 1
 2
 3
-4
+X
 5
 6
```

Options:
- `contextLines`, default: `3`.
- `collapseRanges`, default: `true`.
- `fromFile`, default: `<null>`.
- `fromFileDate`, default: `<null>`.
- `toFile`, default: `<null>`.
- `toFileDate`, default: `<null>`.
- `commonLineThreshold`, default: `6`.


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
