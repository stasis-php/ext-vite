<?php

declare(strict_types=1);

namespace Stasis\Extension\Vite\Tests\Mapper;

use Stasis\Extension\Vite\Mapper\TagProvider;
use PHPUnit\Framework\TestCase;

class TagProviderTest extends TestCase
{
    private TagProvider $provider;

    #[\Override]
    protected function setUp(): void
    {
        $this->provider = new TagProvider();
    }

    public function testModule(): void
    {
        $result = $this->provider->module('/assets/app.js');
        self::assertSame('<script type="module" src="/assets/app.js"></script>', $result);
    }

    public function testScript(): void
    {
        $result = $this->provider->script('/assets/vendor.js');
        self::assertSame('<script src="/assets/vendor.js"></script>', $result);
    }

    public function testStyle(): void
    {
        $result = $this->provider->style('/assets/app.css');
        self::assertSame('<link rel="stylesheet" type="text/css" href="/assets/app.css">', $result);
    }

    public function testPreloadModule(): void
    {
        $result = $this->provider->preloadModule('/assets/app.js');
        self::assertSame('<link rel="modulepreload" href="/assets/app.js">', $result);
    }

    public function testPreloadStyle(): void
    {
        $result = $this->provider->preloadStyle('/assets/app.css');
        self::assertSame('<link rel="preload" as="style" type="text/css" href="/assets/app.css">', $result);
    }

    public function testPreloadFont(): void
    {
        $result = $this->provider->preloadFont('/assets/font.woff2');
        self::assertSame('<link rel="preload" as="font" href="/assets/font.woff2" crossorigin>', $result);
    }

    public function testPreloadFontWithType(): void
    {
        $result = $this->provider->preloadFont('/assets/font.woff2', 'font/woff2');
        self::assertSame('<link rel="preload" as="font" type="font/woff2" href="/assets/font.woff2" crossorigin>', $result);
    }

    public function testPreloadScript(): void
    {
        $result = $this->provider->preloadScript('/assets/app.js');
        self::assertSame('<link rel="preload" as="script" href="/assets/app.js">', $result);
    }

    public function testPreloadImage(): void
    {
        $result = $this->provider->preloadImage('/assets/hero.jpg');
        self::assertSame('<link rel="preload" as="image" href="/assets/hero.jpg">', $result);
    }

    public function testPreloadImageWithType(): void
    {
        $result = $this->provider->preloadImage('/assets/hero.webp', 'image/webp');
        self::assertSame('<link rel="preload" as="image" type="image/webp" href="/assets/hero.webp">', $result);
    }
}
