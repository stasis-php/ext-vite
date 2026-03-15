<?php

declare(strict_types=1);

namespace Stasis\Extension\Vite\Integration;

use Stasis\Extension\Vite\Mapper\AssetMapperInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Twig extension for Vite integration. Provides `asset_import` and `asset_path` functions.
 */
class TwigExtension extends AbstractExtension
{
    public function __construct(
        private AssetMapperInterface $assetMapper,
    ) {}

    #[\Override]
    public function getFunctions(): array
    {
        return [
            new TwigFunction('asset_import', $this->assetMapper->import(...), ['is_safe' => ['html']]),
            new TwigFunction('asset_path', $this->assetMapper->path(...), ['is_safe' => ['html']]),
        ];
    }
}
