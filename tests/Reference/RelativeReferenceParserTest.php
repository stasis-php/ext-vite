<?php

declare(strict_types=1);

namespace Stasis\Extension\Vite\Tests\Reference;

use PHPUnit\Framework\Attributes\DataProvider;
use Stasis\Extension\Vite\Reference\RelativeReferenceParser;
use PHPUnit\Framework\TestCase;

class RelativeReferenceParserTest extends TestCase
{
    #[DataProvider('pathProvider')]
    public function testGetPath(string $baseDir, string $reference, string $expected): void
    {
        $parser = new RelativeReferenceParser($baseDir);
        $result = $parser->getPath($reference);
        self::assertSame($expected, $result);
    }

    public static function pathProvider(): array
    {
        return [
            'basic reference' => ['/assets', 'app.js', '/assets/app.js'],
            'leading slash in reference' => ['/assets', '/app.js', '/assets/app.js'],
            'trailing slash in base dir' => ['/assets/', 'app.js', '/assets/app.js'],
            'both slashes' => ['/assets/', '/app.js', '/assets/app.js'],
            'nested reference' => ['/dist', 'js/components/app.js', '/dist/js/components/app.js'],
            'nested base dir and reference' => ['/public/dist', 'assets/app.js', '/public/dist/assets/app.js'],
        ];
    }
}
