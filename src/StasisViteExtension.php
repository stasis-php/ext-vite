<?php

declare(strict_types=1);

namespace Stasis\Extension\Vite;

use Stasis\EventDispatcher\RouterConfig\RouterConfigData;
use Stasis\EventDispatcher\RouterConfig\RouterConfigListenerInterface;
use Stasis\Extension\ExtensionInterface;
use Stasis\Router\Route\Asset;

final readonly class StasisViteExtension implements ExtensionInterface, RouterConfigListenerInterface
{
    /**
     * @param string $assetsSourcePath Absolute path to the directory with assets produced by Vite,
     *                                 example: `__DIR__ . '/dist_assets/assets`.
     * @param string $assetsPublicPath Relative path from where assets will be served, example: `/assets`.
     */
    public function __construct(
        public string $assetsSourcePath,
        public string $assetsPublicPath,
    ) {}

    #[\Override]
    public function listeners(): iterable
    {
        return [$this];
    }

    #[\Override]
    public function onRouterConfig(RouterConfigData $data): void
    {
        $data->routes([
            new Asset($this->assetsPublicPath, $this->assetsSourcePath),
        ]);
    }
}
