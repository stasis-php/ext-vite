<?php

declare(strict_types=1);

namespace Stasis\Extension\Vite;

use Stasis\EventDispatcher\RouterConfig\RouterConfigData;
use Stasis\EventDispatcher\RouterConfig\RouterConfigListenerInterface;
use Stasis\Extension\ExtensionInterface;
use Stasis\Extension\Vite\Integration\TwigExtension;
use Stasis\Extension\Vite\Reference\ReferenceParserInterface;
use Stasis\Extension\Vite\Reference\RelativeReferenceParser;
use Stasis\Router\Route\Asset;
use Twig\Environment;

final readonly class StasisViteExtension implements ExtensionInterface, RouterConfigListenerInterface
{
    private function __construct(
        public AssetMapperInterface $assetMapper,
        private string $assetsSourcePath,
        private string $assetsRoutePath,
    ) {}

    /**
     * Creates a new Stasis Vite extension instance with default {@see RelativeReferenceParser}.
     *
     * @param string $assetsSourcePath Absolute path to the directory with assets produced by Vite,
     *                                 example: `__DIR__ . '/dist_assets/assets'`.
     * @param string $manifestPath Absolute path to the Vite manifest file,
     *                             example: `__DIR__ . '/dist_assets/manifest.json'`.
     * @param string $assetsRoutePath Public URL path from where assets will be served, example: `/assets`.
     */
    public static function create(
        string $assetsSourcePath,
        string $manifestPath,
        string $assetsRoutePath = '/assets',
    ): self {
        $referenceParser = new RelativeReferenceParser($assetsSourcePath);
        return self::createWithReferenceParser($assetsSourcePath, $manifestPath, $referenceParser, $assetsRoutePath);
    }

    /**
     * Creates a new Stasis Vite extension instance with a custom reference parser.
     *
     * @param string $assetsSourcePath Absolute path to the directory with assets produced by Vite,
     *                                 example: `__DIR__ . '/dist_assets/assets'`.
     * @param string $manifestPath Absolute path to the Vite manifest file,
     *                             example: `__DIR__ . '/dist_assets/assets/manifest.json'`.
     * @param ReferenceParserInterface $referenceParser Custom reference parser implementation.
     * @param string $assetsRoutePath Public URL path from where assets will be served, example: `/assets`.
     */
    public static function createWithReferenceParser(
        string $assetsSourcePath,
        string $manifestPath,
        ReferenceParserInterface $referenceParser,
        string $assetsRoutePath = '/assets',
    ): self {
        $assetMapper = AssetMapperFactory::create($manifestPath, $referenceParser);
        return new self($assetMapper, $assetsSourcePath, $assetsRoutePath);
    }

    /**
     * Registers Stasis Vite Twig extension with the provided Twig environment.
     * Note: This method mutates the Twig environment and returns $this for chaining.
     */
    public function withTwig(Environment $twig): self
    {
        $extension = new TwigExtension($this->assetMapper);
        $twig->addExtension($extension);
        return $this;
    }

    #[\Override]
    public function listeners(): iterable
    {
        return [$this];
    }

    #[\Override]
    public function onRouterConfig(RouterConfigData $data): void
    {
        $data->routes([
            new Asset($this->assetsRoutePath, $this->assetsSourcePath),
        ]);
    }
}
