<?php

declare(strict_types=1);

namespace Stasis\Extension\Vite\Tests\Mapper;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Stasis\Extension\Vite\Asset\AssetFactory;
use Stasis\Extension\Vite\Manifest\Manifest;
use Stasis\Extension\Vite\Mapper\ManifestAssetMapper;
use Stasis\Extension\Vite\Mapper\TagProvider;
use Stasis\Extension\Vite\Reference\ReferenceParserInterface;

final class ManifestAssetMapperTest extends TestCase
{
    private MockObject&ReferenceParserInterface $referenceParser;
    private ManifestAssetMapper $mapper;

    #[\Override]
    protected function setUp(): void
    {
        $this->referenceParser = $this->createMock(ReferenceParserInterface::class);

        $this->mapper = new ManifestAssetMapper(
            new Manifest(__DIR__ . '/../manifest.json'),
            $this->referenceParser,
            new AssetFactory(),
            new TagProvider(),
        );
    }

    public function testImport(): void
    {
        $reference = 'main.js';
        $referencePath = 'src/resources/assets/main.js';

        $this->referenceParser
            ->expects($this->once())
            ->method('getPath')
            ->with($reference)
            ->willReturn($referencePath);

        $result = $this->mapper->import($reference);

        $expected = '<link rel="preload" as="font" type="font/woff2" href="/assets/fonts/font-FIwubZjA.woff2" crossorigin>'
            . "\n"
            . '<link rel="stylesheet" type="text/css" href="/assets/main-CIbdUyYy.css">'
            . "\n"
            . '<script type="module" src="/assets/main-ShnCcQT4.js"></script>'
            . "\n"
            . '<link rel="modulepreload" href="/assets/main-ShnCcQT4.js">';

        self::assertSame($expected, $result);
    }

    public function testImportNonModule(): void
    {
        $reference = 'favicon.ico';
        $referencePath = 'src/resources/assets/favicon.ico';

        $this->referenceParser
            ->expects($this->once())
            ->method('getPath')
            ->with($reference)
            ->willReturn($referencePath);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Unable to import asset "favicon.ico". Asset reference is not an entrypoint.');

        $this->mapper->import($reference);
    }

    public function testPath(): void
    {
        $reference = 'favicon.ico';
        $referencePath = 'src/resources/assets/favicon.ico';

        $this->referenceParser
            ->expects($this->once())
            ->method('getPath')
            ->with($reference)
            ->willReturn($referencePath);

        $result = $this->mapper->path($reference);

        self::assertSame('/assets/favicon-C4ICqSsa.ico', $result);
    }
}
