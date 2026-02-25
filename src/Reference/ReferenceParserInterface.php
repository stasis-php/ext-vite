<?php

namespace Stasis\Extension\Vite\Reference;

interface ReferenceParserInterface
{
    public function getPath(string $reference): string;
}
