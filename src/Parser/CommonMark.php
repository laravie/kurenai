<?php

namespace Kurenai\Parser;

use League\CommonMark\CommonMarkConverter;
use Kurenai\Contracts\MarkdownParser as ParserContract;

class CommonMark extends CommonMarkConverter implements ParserContract
{
    /**
     * Parse markdown content.
     *
     * @param  string|null  $content
     *
     * @return string
     */
    public function render(?string $content): string
    {
        return $this->convertToHtml($content);
    }
}
