<?php

declare(strict_types=1);

namespace Stasis\Extension\Vite;

interface AssetMapperInterface
{
    /**
     * Returns tags to be inserted in the HTML head.
     * Includes imports and preload tags. Only entrypoint may be imported.
     */
    public function import(string $reference): string;

    /**
     * Returns the public path to the asset.
     * Quotes in the path are urlencoded to be safely used inside HTML attributes.
     */
    public function path(string $reference): string;
}
