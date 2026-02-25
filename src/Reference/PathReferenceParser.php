<?php

declare(strict_types=1);

namespace Stasis\Extension\Vite\Reference;

class PathReferenceParser implements ReferenceParserInterface
{
    private readonly string $baseDir;

    public function __construct(string $baseDir) {
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
