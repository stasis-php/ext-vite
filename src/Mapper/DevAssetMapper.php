<?php

declare(strict_types=1);

namespace Stasis\Extension\Vite\Mapper;

use Stasis\Extension\Vite\Asset\Asset;
use Stasis\Extension\Vite\Asset\AssetFactory;
use Stasis\Extension\Vite\AssetMapperInterface;
use Stasis\Extension\Vite\Reference\ReferenceParserInterface;

/**
 * @internal
 */
class DevAssetMapper implements AssetMapperInterface
{
    public function __construct(
        private ReferenceParserInterface $referenceParser,
        private AssetFactory $assetFactory,
        private TagProvider $tagProvider,
    ) {}

    #[\Override]
    public function import(string $reference): string
    {
        $tags = [];

        $asset = $this->getAsset($reference);

        if (!$asset->isScript()) {
            throw new \LogicException(sprintf(
                'Unable to import asset "%s". Only JavaScript modules can be imported.',
                $reference,
            ));
        }

        $tags[] = $this->tagProvider->module($asset->publicPath);
        $tags[] = $this->tagProvider->preloadModule($asset->publicPath);

        return implode("\n", $tags);
    }

    #[\Override]
    public function path(string $reference): string
    {
        return $this->getAsset($reference)->publicPath;
    }

    private function getAsset(string $reference): Asset
    {
        $referencePath = $this->referenceParser->getPath($reference);
        return $this->assetFactory->fromSourcePath($referencePath, '//localhost:5173');
    }
}
