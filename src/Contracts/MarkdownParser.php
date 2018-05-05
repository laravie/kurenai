<?php

namespace Kurenai\Contracts;

interface MarkdownParser
{
    /**
     * Parse markdown content.
     *
     * @param  string|null  $content
     *
     * @return string
     */
    public function render(?string $content): string;
}
