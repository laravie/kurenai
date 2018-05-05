<?php

namespace Kurenai\Parser;

use Parsedown as Parser;
use Kurenai\Contracts\MarkdownParser as ParserContract;

class Parsedown extends Parser implements ParserContract
{
    /**
     * Parse markdown content.
     *
     * @param  string|null  $content
     * @return string
     */
    public function render(?string $content): string
    {
        return $this->text($content);
    }
}
