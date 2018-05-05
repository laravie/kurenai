<?php

namespace Kurenai\Parser\TestCase;

use Kurenai\Document;
use Kurenai\Parser\CommonMark;
use PHPUnit\Framework\TestCase;

class CommonMarkTest extends TestCase
{
    public function testDocumentHtmlContentCanBeReturned()
    {
        $d = new Document(new CommonMark());
        $d->setContent('Foo **Bar** Baz');
        $e = "<p>Foo <strong>Bar</strong> Baz</p>\n";
        $this->assertEquals($e, $d->getHtmlContent());
    }
}
