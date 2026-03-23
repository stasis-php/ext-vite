<?php

declare(strict_types=1);

namespace Stasis\Extension\Vite\Tests\Mapper;

use PHPUnit\Framework\TestCase;
use Stasis\Extension\Vite\Mapper\AssetMapperFactory;
use Stasis\Extension\Vite\Mapper\DevAssetMapper;
use Stasis\Extension\Vite\Mapper\ManifestAssetMapper;
use Stasis\Extension\Vite\Reference\ReferenceParserInterface;

final class AssetMapperFactoryTest extends TestCase
{
    public function testCreateManifest(): void
    {
        $manifestPath = __DIR__ . '/../manifest.json';
        $referenceParser = self::createStub(ReferenceParserInterface::class);
        $assetMapper = AssetMapperFactory::create($manifestPath, $referenceParser);
        self::assertInstanceOf(ManifestAssetMapper::class, $assetMapper);
    }

    public function testCreateDev(): void
    {
        putenv('APP_ENV=dev');
        $manifestPath = __DIR__ . '/../manifest.json';
        $referenceParser = self::createStub(ReferenceParserInterface::class);
        $assetMapper = AssetMapperFactory::create($manifestPath, $referenceParser);
        putenv('APP_ENV');
        self::assertInstanceOf(DevAssetMapper::class, $assetMapper);
    }
}
