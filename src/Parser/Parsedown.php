<?php namespace Kurenai\Parser;

use Parsedown as Parser;
use Kurenai\Contracts\MarkdownParser as ParserContract;

class Parsedown extends Parser implements ParserContract
{
    public function render($content)
    {
        return $this->text($content);
    }
}
