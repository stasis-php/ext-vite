<?php

declare(strict_types=1);

namespace Stasis\Extension\Vite\Tests\Manifest;

use Stasis\Extension\Vite\Manifest\Manifest;
use Stasis\Extension\Vite\Manifest\ManifestEntry;
use PHPUnit\Framework\TestCase;

final class ManifestTest extends TestCase
{
    private Manifest $manifest;

    #[\Override]
    protected function setUp(): void
    {
        $this->manifest = new Manifest(__DIR__ . '/../manifest.json');
    }

    public function testLoadManifest(): void
    {
        self::assertInstanceOf(Manifest::class, $this->manifest);
    }

    public function testManifestNotExists(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Manifest file "/nonexistent/manifest.json" not exist. Did you run "vite build"?');
        new Manifest('/nonexistent/manifest.json');
    }

    public function testGet(): void
    {
        $entry = $this->manifest->get('src/resources/assets/main.js');

        $expected = new ManifestEntry(
            'src/resources/assets/main.js',
            'assets/main-ShnCcQT4.js',
            true,
            ['assets/main-CIbdUyYy.css'],
            ['assets/fonts/font-FIwubZjA.woff2'],
        );

        self::assertEquals($expected, $entry);
    }

    public function testGetWithDefaults(): void
    {
        $entry = $this->manifest->get('src/resources/assets/favicon.ico');

        $expected = new ManifestEntry(
            'src/resources/assets/favicon.ico',
            'assets/favicon-C4ICqSsa.ico',
            false,
            [],
            [],
        );

        self::assertEquals($expected, $entry);
    }

    public function testGetNotExistent(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Asset "nonexistent.js" not found in manifest.');
        $this->manifest->get('nonexistent.js');
    }
}
