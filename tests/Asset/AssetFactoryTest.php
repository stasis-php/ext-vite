<?php

declare(strict_types=1);

namespace Stasis\Extension\Vite\Tests\Asset;

use PHPUnit\Framework\Attributes\DataProvider;
use Stasis\Extension\Vite\Asset\Asset;
use Stasis\Extension\Vite\Asset\AssetFactory;
use PHPUnit\Framework\TestCase;

final class AssetFactoryTest extends TestCase
{
    private AssetFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new AssetFactory();
    }

    #[DataProvider('assetCreationProvider')]
    public function testFromSourcePath(string $sourcePath, string $base, Asset $expected): void
    {
        $asset = $this->factory->fromSourcePath($sourcePath, $base);
        self::assertEquals($expected, $asset);
    }

    public static function assetCreationProvider(): array
    {
        return [
            'default' => ['app.js', '/assets', new Asset('app.js', '/assets/app.js', 'js', 'text/javascript')],
            'leading slash in source path' => ['/app.js', '/assets', new Asset('/app.js', '/assets/app.js', 'js', 'text/javascript')],
            'trailing slash in base' => ['app.js', '/assets/', new Asset('app.js', '/assets/app.js', 'js', 'text/javascript')],
            'quotes in path' => ['file"with\'quotes.js', '/', new Asset('file"with\'quotes.js', '/file%22with%27quotes.js', 'js', 'text/javascript')],
            'js file' => ['script.js', '/', new Asset('script.js', '/script.js', 'js', 'text/javascript')],
            'css file' => ['styles.css', '/', new Asset('styles.css', '/styles.css', 'css', 'text/css')],
            'ttf font' => ['font.ttf', '/', new Asset('font.ttf', '/font.ttf', 'ttf', 'font/ttf')],
            'otf font' => ['font.otf', '/', new Asset('font.otf', '/font.otf', 'otf', 'font/otf')],
            'woff font' => ['font.woff', '/', new Asset('font.woff', '/font.woff', 'woff', 'font/woff')],
            'woff2 font' => ['font.woff2', '/', new Asset('font.woff2', '/font.woff2', 'woff2', 'font/woff2')],
            'png image' => ['image.png', '/', new Asset('image.png', '/image.png', 'png', 'image/png')],
            'jpg image' => ['image.jpg', '/', new Asset('image.jpg', '/image.jpg', 'jpg', 'image/jpeg')],
            'jpeg image' => ['image.jpeg', '/', new Asset('image.jpeg', '/image.jpeg', 'jpeg', 'image/jpeg')],
            'gif image' => ['image.gif', '/', new Asset('image.gif', '/image.gif', 'gif', 'image/gif')],
            'svg image' => ['image.svg', '/', new Asset('image.svg', '/image.svg', 'svg', 'image/svg+xml')],
            'ico image' => ['image.ico', '/', new Asset('image.ico', '/image.ico', 'ico', 'image/vnd.microsoft.ico')],
            'unknown extension' => ['file.unknown', '/', new Asset('file.unknown', '/file.unknown', 'unknown', null)],
            'no extension' => ['file', '/', new Asset('file', '/file', '', null)],
        ];
    }
}
