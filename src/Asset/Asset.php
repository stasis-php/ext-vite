<?php

declare(strict_types=1);

namespace Stasis\Extension\Vite\Asset;

/**
 * @internal
 */
final readonly class Asset
{
    public function __construct(
        public string $sourcePath,
        public string $publicPath,
        public string $extension,
        public ?string $mime = null,
    ) {}

    public function isScript(): bool
    {
        return $this->extension === 'js';
    }

    public function isFont(): bool
    {
        return in_array($this->extension, ['ttf', 'otf', 'woff', 'woff2'], true);
    }
}
