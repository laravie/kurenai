<?php

namespace Kurenai\Parser\TestCase;

use Kurenai\Document;
use Kurenai\Parser\Parsedown;
use Kurenai\Parser\ParsedownExtra;

class ParsedownParserTest extends \PHPUnit_Framework_TestCase
{
    public function testDocumentHtmlContentCanBeReturned()
    {
        $d = new Document(new Parsedown);
        $d->setContent('Foo **Bar** Baz');
        $e = "<p>Foo <strong>Bar</strong> Baz</p>";
        $this->assertEquals($e, $d->getHtmlContent());
    }

    public function testDocumentCanParseExtraMarkdown()
    {
        $document = new Document(new ParsedownExtra);
        $document->setContent("~~~\nCode Block\n~~~");
        $expected = "<pre><code>Code Block</code></pre>";
        $this->assertEquals($expected, $document->getHtmlContent(true));
    }
}
