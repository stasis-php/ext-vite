<?php

declare(strict_types=1);

namespace Stasis\Extension\Vite\Mapper;

use Stasis\Extension\Vite\Asset\AssetFactory;
use Stasis\Extension\Vite\Manifest\Manifest;
use Stasis\Extension\Vite\Reference\ReferenceParserInterface;

/**
 * @internal
 */
class ManifestAssetMapper implements AssetMapperInterface
{
    private const string BASE = '/';

    public function __construct(
        private Manifest $manifest,
        private ReferenceParserInterface $referenceParser,
        private AssetFactory $assetFactory,
        private TagProvider $tagProvider,
    ) {}

    #[\Override]
    public function import(string $reference): string
    {
        $tags = [];

        $referencePath = $this->referenceParser->getPath($reference);
        $entry = $this->manifest->get($referencePath);
        $asset = $this->assetFactory->fromSourcePath($entry->path, self::BASE);

        if (!$entry->isModule) {
            throw new \LogicException(sprintf(
                'Unable to import asset "%s". Asset reference is not an entrypoint.',
                $reference,
            ));
        }

        foreach ($entry->assets as $path) {
            $moduleAsset = $this->assetFactory->fromSourcePath($path, self::BASE);
            if ($moduleAsset->isFont()) {
                $tags[] = $this->tagProvider->preloadFont($moduleAsset->publicPath, $moduleAsset->mime);
            }
        }

        foreach ($entry->css as $path) {
            $cssAsset = $this->assetFactory->fromSourcePath($path, self::BASE);
            $tags[] = $this->tagProvider->style($cssAsset->publicPath);
        }

        $tags[] = $this->tagProvider->module($asset->publicPath);
        $tags[] = $this->tagProvider->preloadModule($asset->publicPath);

        return implode("\n", $tags);
    }

    #[\Override]
    public function path(string $reference): string
    {
        $referencePath = $this->referenceParser->getPath($reference);
        $entry = $this->manifest->get($referencePath);
        $asset = $this->assetFactory->fromSourcePath($entry->path, self::BASE);
        return $asset->publicPath;
    }
}
