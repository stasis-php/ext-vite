<?php

declare(strict_types=1);

namespace Stasis\Extension\Vite\Tests;

use PHPUnit\Framework\TestCase;
use Stasis\EventDispatcher\RouterConfig\RouterConfigData;
use Stasis\Extension\Vite\Mapper\AssetMapperInterface;
use Stasis\Extension\Vite\Reference\ReferenceParserInterface;
use Stasis\Extension\Vite\StasisViteExtension;
use Stasis\Router\Route\Asset;
use Twig\Environment;
use Twig\Extension\AbstractExtension;

final class StasisViteExtensionTest extends TestCase
{
    public function testCreate(): void
    {
        $extension = StasisViteExtension::create(
            assetsSourcePath: __DIR__ . '/dist_assets',
            manifestPath: __DIR__ . '/manifest.json',
            assetsRoutePath: '/assets',
        );

        self::assertInstanceOf(StasisViteExtension::class, $extension);
        self::assertInstanceOf(AssetMapperInterface::class, $extension->assetMapper);
    }

    public function testCreateWithReferenceParser(): void
    {
        $referenceParser = $this->createStub(ReferenceParserInterface::class);

        $extension = StasisViteExtension::createWithReferenceParser(
            assetsSourcePath: __DIR__ . '/dist_assets',
            manifestPath: __DIR__ . '/manifest.json',
            referenceParser: $referenceParser,
            assetsRoutePath: '/assets',
        );

        self::assertInstanceOf(StasisViteExtension::class, $extension);
        self::assertInstanceOf(AssetMapperInterface::class, $extension->assetMapper);
    }

    public function testWithTwig(): void
    {
        $twig = $this->createMock(Environment::class);
        $twig
            ->expects($this->once())
            ->method('addExtension')
            ->with($this->isInstanceOf(AbstractExtension::class));

        $extension = StasisViteExtension::create(
            assetsSourcePath: __DIR__ . '/dist_assets',
            manifestPath: __DIR__ . '/manifest.json',
            assetsRoutePath: '/assets',
        );

        $result = $extension->withTwig($twig);

        self::assertSame($extension, $result);
    }

    public function testListeners(): void
    {
        $extension = StasisViteExtension::create(
            assetsSourcePath: __DIR__ . '/dist_assets',
            manifestPath: __DIR__ . '/manifest.json',
            assetsRoutePath: '/assets',
        );

        $listeners = iterator_to_array($extension->listeners());

        self::assertSame([$extension], $listeners);
    }

    public function testOnRouterConfig(): void
    {
        $extension = StasisViteExtension::create(
            assetsSourcePath: __DIR__ . '/dist_assets',
            manifestPath: __DIR__ . '/manifest.json',
            assetsRoutePath: '/assets',
        );

        $routerConfigData = $this->createMock(RouterConfigData::class);
        $routerConfigData
            ->expects($this->once())
            ->method('routes')
            ->with([new Asset('/assets', __DIR__ . '/dist_assets')]);

        $extension->onRouterConfig($routerConfigData);
    }
}
