<?php

namespace Stasis\Extension\Vite\Tests;

use Stasis\Extension\Vite\AssetMapperFactory;
use PHPUnit\Framework\TestCase;
use Stasis\Extension\Vite\Reference\ReferenceParserInterface;

class AssetMapperFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $manifestPath = __DIR__ . '/manifest.json';
        $referenceParser = $this->createStub(ReferenceParserInterface::class);
        AssetMapperFactory::create($manifestPath, $referenceParser);
        $this->expectNotToPerformAssertions();
    }
}
