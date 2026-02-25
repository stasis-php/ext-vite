<?php

declare(strict_types=1);

namespace Stasis\Extension\Vite\Manifest;

/**
 * @internal
 */
final readonly class ManifestEntry
{
    public function __construct(
        public string $src,
        public string $path,
        public bool $isModule = false,
        /** @var array<string> */
        public array $css = [],
        /** @var array<string> */
        public array $assets = [],
    ) {}
}
