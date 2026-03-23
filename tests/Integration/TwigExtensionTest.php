<?php

declare(strict_types=1);

namespace Stasis\Extension\Vite\Tests\Integration;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Stasis\Extension\Vite\Integration\TwigExtension;
use Stasis\Extension\Vite\Mapper\AssetMapperInterface;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

final class TwigExtensionTest extends TestCase
{
    private Environment $twig;
    private TwigExtension $extension;
    private AssetMapperInterface&MockObject $assetMapper;

    #[\Override]
    public function setUp(): void
    {
        $this->assetMapper = $this->createMock(AssetMapperInterface::class);
        $this->twig = new Environment(new ArrayLoader());
        $this->extension = new TwigExtension($this->assetMapper);
        $this->twig->addExtension($this->extension);
    }

    public function testAssetImport(): void
    {
        $this->assetMapper
            ->expects($this->once())
            ->method('import')
            ->with('app.js')
            ->willReturn('<script type="module" src="/assets/app.js"></script>');

        $template = '{{ asset_import("app.js") }}';
        $actual = $this->twig->createTemplate($template)->render();
        self::assertSame('<script type="module" src="/assets/app.js"></script>', $actual);
    }

    public function testAssetPath(): void
    {
        $this->assetMapper
            ->expects($this->once())
            ->method('path')
            ->with('app.js')
            ->willReturn('/assets/app.js');

        $template = 'Path: {{ asset_path("app.js") }}';
        $actual = $this->twig->createTemplate($template)->render();
        self::assertSame('Path: /assets/app.js', $actual);
    }
}
