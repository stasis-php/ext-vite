<?php

declare(strict_types=1);

namespace Stasis\Extension\Vite\Tests\Asset;

use PHPUnit\Framework\Attributes\DataProvider;
use Stasis\Extension\Vite\Asset\Asset;
use PHPUnit\Framework\TestCase;

final class AssetTest extends TestCase
{
    #[DataProvider('scriptExtensionProvider')]
    public function testIsScript(string $extension, bool $expected): void
    {
        $asset = new Asset("/src/path.{$extension}", "/dist/path.{$extension}", $extension);
        $actual = $asset->isScript();
        self::assertEquals($expected, $actual);
    }

    public static function scriptExtensionProvider(): array
    {
        return [
            ['js', true],
            ['css', false],
        ];
    }

    #[DataProvider('fontExtensionProvider')]
    public function testIsFontReturnsTrueForFontExtensions(string $extension, bool $expected): void
    {
        $asset = new Asset("/src/path.{$extension}", "/dist/path.{$extension}", $extension);
        $actual = $asset->isFont();
        self::assertEquals($expected, $actual);
    }

    public static function fontExtensionProvider(): array
    {
        return [
            'ttf' => ['ttf', true],
            'otf' => ['otf', true],
            'woff' => ['woff', true],
            'woff2' => ['woff2', true],
            'png' => ['png', false],
        ];
    }
}
