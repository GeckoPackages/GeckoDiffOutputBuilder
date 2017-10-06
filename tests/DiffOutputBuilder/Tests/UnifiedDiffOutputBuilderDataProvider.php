<?php

/*
 * This file is part of the GeckoPackages.
 *
 * (c) GeckoPackages https://github.com/GeckoPackages
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace GeckoPackages\DiffOutputBuilder\Tests;

/**
 * @author SpacePossum
 *
 * @coversNothing
 *
 * @internal
 */
final class UnifiedDiffOutputBuilderDataProvider
{
    public static function provideOutputBuildingCases()
    {
        return [
            [
'--- input.txt
+++ output.txt
@@ -1,3 +1,4 @@
+b
 '.'
 '.'
 '.'
@@ -16,5 +17,4 @@
 '.'
 '.'
 '.'
-
-B
+A
',
                "\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\nB\n",
                "b\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\nA\n",
                [
                    'fromFile' => 'input.txt',
                    'toFile' => 'output.txt',
                ],
            ],
            [
'--- '.__FILE__."\t2017-10-02 17:38:11.586413675 +0100
+++ output1.txt\t2017-10-03 12:09:43.086719482 +0100
@@ -1,1 +1,1 @@
-B
+X
",
                "B\n",
                "X\n",
                [
                    'fromFile' => __FILE__,
                    'fromFileDate' => '2017-10-02 17:38:11.586413675 +0100',
                    'toFile' => 'output1.txt',
                    'toFileDate' => '2017-10-03 12:09:43.086719482 +0100',
                    'collapseRanges' => false,
                ],
            ],
            [
'--- input.txt
+++ output.txt
@@ -1 +1 @@
-B
+X
',
                "B\n",
                "X\n",
                [
                    'fromFile' => 'input.txt',
                    'toFile' => 'output.txt',
                    'collapseRanges' => true,
                ],
            ],
        ];
    }

    public static function provideSample()
    {
        return [
            [
'--- input.txt
+++ output.txt
@@ -1,6 +1,6 @@
 1
 2
 3
-4
+X
 5
 6
',
                "1\n2\n3\n4\n5\n6\n",
                "1\n2\n3\nX\n5\n6\n",
                [
                    'fromFile' => 'input.txt',
                    'toFile' => 'output.txt',
                ],
            ],
        ];
    }

    public static function provideBasicDiffGeneration()
    {
        return [
            [
"--- input.txt
+++ output.txt
@@ -1,2 +1 @@
-A
-B
+A\rB
",
                "A\nB\n",
                "A\rB\n",
            ],
            [
"--- input.txt
+++ output.txt
@@ -1 +1 @@
-
+\r
\\ No newline at end of file
",
                "\n",
                "\r",
            ],
            [
"--- input.txt
+++ output.txt
@@ -1 +1 @@
-\r
\\ No newline at end of file
+
",
                "\r",
                "\n",
            ],
            [
'--- input.txt
+++ output.txt
@@ -1,3 +1,3 @@
 X
 A
-A
+B
',
                "X\nA\nA\n",
                "X\nA\nB\n",
            ],
            [
'--- input.txt
+++ output.txt
@@ -1,3 +1,3 @@
 X
 A
-A
\ No newline at end of file
+B
',
                "X\nA\nA",
                "X\nA\nB\n",
            ],
            [
'--- input.txt
+++ output.txt
@@ -1,3 +1,3 @@
 A
 A
-A
+B
\ No newline at end of file
',
                "A\nA\nA\n",
                "A\nA\nB",
            ],
            [
'--- input.txt
+++ output.txt
@@ -1 +1 @@
-A
\ No newline at end of file
+B
\ No newline at end of file
',
                'A',
                'B',
            ],
        ];
    }
}
