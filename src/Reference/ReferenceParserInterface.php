<?php

declare(strict_types=1);

namespace Stasis\Extension\Vite\Reference;

/**
 * Interface for reference parsers. Implement in need of any custom reference parsing logic.
 * For example, if you want to have references following a modular or bundle structure.
 */
interface ReferenceParserInterface
{
    /**
     * Parse reference and return a path to the asset recognizable by manifest.
     *
     * @param string $reference Reference to parse.
     * @return string Path to the asset recognizable by manifest.
     */
    public function getPath(string $reference): string;
}
