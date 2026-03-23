<?php

declare(strict_types=1);

namespace Stasis\Extension\Vite\Tests\Mapper;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Stasis\Extension\Vite\Asset\AssetFactory;
use Stasis\Extension\Vite\Mapper\DevAssetMapper;
use Stasis\Extension\Vite\Mapper\TagProvider;
use Stasis\Extension\Vite\Reference\ReferenceParserInterface;

final class DevAssetMapperTest extends TestCase
{
    private MockObject&ReferenceParserInterface $referenceParser;
    private DevAssetMapper $mapper;

    #[\Override]
    protected function setUp(): void
    {
        $this->referenceParser = $this->createMock(ReferenceParserInterface::class);

        $this->mapper = new DevAssetMapper(
            $this->referenceParser,
            new AssetFactory(),
            new TagProvider(),
        );
    }

    public function testImport(): void
    {
        $reference = 'app.js';
        $referencePath = 'src/app.js';

        $this->referenceParser
            ->expects($this->once())
            ->method('getPath')
            ->with($reference)
            ->willReturn($referencePath);

        $result = $this->mapper->import($reference);

        $expected = '<script type="module" src="//localhost:5173/src/app.js"></script>'
            . "\n"
            . '<link rel="modulepreload" href="//localhost:5173/src/app.js">';

        self::assertSame($expected, $result);
    }

    public function testImportNonScript(): void
    {
        $reference = 'styles.css';
        $referencePath = 'src/styles.css';

        $this->referenceParser
            ->expects($this->once())
            ->method('getPath')
            ->with($reference)
            ->willReturn($referencePath);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Unable to import asset "styles.css". Only JavaScript modules can be imported.');

        $this->mapper->import($reference);
    }

    public function testPath(): void
    {
        $reference = 'app.js';
        $referencePath = 'src/app.js';

        $this->referenceParser
            ->expects($this->once())
            ->method('getPath')
            ->with($reference)
            ->willReturn($referencePath);

        $result = $this->mapper->path($reference);

        self::assertSame('//localhost:5173/src/app.js', $result);
    }
}
