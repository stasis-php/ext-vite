<?php

declare(strict_types=1);

namespace Stasis\Extension\Vite\Reference;

/**
 * Simple reference parser for assets in a single directory.
 */
final readonly class RelativeReferenceParser implements ReferenceParserInterface
{
    private string $baseDir;

    public function __construct(string $baseDir)
    {
        $this->baseDir = rtrim($baseDir, '/');
    }

    #[\Override]
    public function getPath(string $reference): string
    {
        return sprintf(
            '%s/%s',
            $this->baseDir,
            ltrim($reference, '/'),
        );
    }
}
