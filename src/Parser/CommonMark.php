<?php namespace Kurenai\Parser;

use League\CommonMark\CommonMarkConverter;
use Kurenai\Contracts\MarkdownParser as ParserContract;

class CommonMark extends CommonMarkConverter implements ParserContract
{
    public function render($content)
    {
        return $this->convertToHtml($content);
    }
}
