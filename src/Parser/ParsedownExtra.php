<?php

namespace Kurenai\Parser;

use ParsedownExtra as Parser;
use Kurenai\Contracts\MarkdownParser as ParserContract;

class ParsedownExtra extends Parser implements ParserContract
{
    public function render($content)
    {
        return $this->text($content);
    }
}
