<?php

declare(strict_types=1);

namespace Stasis\Extension\Vite\Mapper;

use Stasis\Extension\Vite\Asset\AssetFactory;
use Stasis\Extension\Vite\Manifest\Manifest;
use Stasis\Extension\Vite\Reference\ReferenceParserInterface;

/**
 * @internal
 */
class AssetMapperFactory
{
    /**
     * Creates an instance of Vite asset mapper {@see AssetMapperInterface}.
     */
    public static function create(string $manifestPath, ReferenceParserInterface $referenceParser): AssetMapperInterface
    {
        $assetFactory = new AssetFactory();
        $tagProvider = new TagProvider();

        if (self::isDev()) {
            return new DevAssetMapper($referenceParser, $assetFactory, $tagProvider);
        }

        $manifest = new Manifest($manifestPath);
        return new ManifestAssetMapper($manifest, $referenceParser, $assetFactory, $tagProvider);
    }

    private static function isDev(): bool
    {
        return getenv('APP_ENV') === 'dev';
    }
}
