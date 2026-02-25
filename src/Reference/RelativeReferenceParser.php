<?php

declare(strict_types=1);

namespace Stasis\Extension\Vite\Reference;

/**
 * Simple reference parser for assets in a single directory.
 */
class RelativeReferenceParser implements ReferenceParserInterface
{
    private readonly string $baseDir;

    public function __construct(string $baseDir)
    {
        $this->baseDir = rtrim($baseDir, '/');
    }

    public function getPath(string $reference): string
    {
        return sprintf(
            '%s/%s',
            $this->baseDir,
            ltrim($reference, '/'),
        );
    }
}
