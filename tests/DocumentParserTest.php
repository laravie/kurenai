<?php

namespace Kurenai\TestCase;

use Kurenai\Document;
use Kurenai\DocumentParser;
use Kurenai\Parser\Parsedown;
use PHPUnit\Framework\TestCase;

class DocumentParserTest extends TestCase
{
    public function testParseSectionCanParseOffset()
    {
        $d = new DocumentParser(new Document(new Parsedown()));
        $f = "first\n-----\nsecond";
        $this->assertEquals('first', $d->parseSection($f, 0));
        $this->assertEquals('second', $d->parseSection($f, 1));
    }

    public function testParseSectionCanHandleExtraNewLines()
    {
        $d = new DocumentParser(new Document(new Parsedown()));
        $f = "first\n\n\n-----\n\n\nsecond";
        $this->assertEquals('first', $d->parseSection($f, 0));
        $this->assertEquals('second', $d->parseSection($f, 1));
    }

    public function testParseSectionCanHandleMultipleSeparators()
    {
        $d = new DocumentParser(new Document(new Parsedown()));
        $f = "first\n\n\n-----\n--\n\n\nsecond";
        $this->assertEquals('first', $d->parseSection($f, 0));
        $this->assertEquals("--\n\n\nsecond", $d->parseSection($f, 1));
    }

    /**
     * @expectedException \Kurenai\Exceptions\TooFewSectionsException
     */
    public function testParseSectionBreaksWithoutNewLines()
    {
        $d = new DocumentParser(new Document(new Parsedown()));
        $f = "first-----second";
        $d->parseSection($f, 0);
    }

    /**
     * @expectedException \Kurenai\Exceptions\TooFewSectionsException
     * @return [type] [description]
     */
    public function testParseSectionBreaksWhenSeparatorHasLessThanThreeDashes()
    {
        $d = new DocumentParser(new Document(new Parsedown()));
        $f = "first\n--\nsecond";
        $d->parseSection($f, 0);
    }

    public function testParseSectionWorksWithLotsOfDashes()
    {
        $d = new DocumentParser(new Document(new Parsedown()));
        $f = "first\n------------------------------\nsecond";
        $d->parseSection($f, 0);
        $this->assertEquals('first', $d->parseSection($f, 0));
        $this->assertEquals('second', $d->parseSection($f, 1));
    }

    public function testDocumentHeaderSectionCanBeParsed()
    {
        $d = new DocumentParser(new Document(new Parsedown()));
        $f = file_get_contents(__DIR__.'/fixtures/document_01.md');
        $s = $d->parseHeader($f);
        $this->assertEquals('metadata section', $s);
    }

    public function testDocumentContentSectionCanBeParsed()
    {
        $d = new DocumentParser(new Document(new Parsedown()));
        $f = file_get_contents(__DIR__.'/fixtures/document_01.md');
        $s = $d->parseContent($f);
        $this->assertEquals('content section', $s);
    }

    public function testMetadataCanBeParsed()
    {
        $d = new DocumentParser(new Document(new Parsedown()));
        $f = file_get_contents(__DIR__.'/fixtures/document_02.md');
        $s = $d->parseMetadata($f);
        $this->assertCount(2, $s);
        $this->assertEquals(array('Foo' => 'Bar', 'Baz' => 'Boo'), $s);
    }

    /**
     * @expectedException \Symfony\Component\Yaml\Exception\ParseException
     */
    public function testMetadataCanBeParsedWithExtraColons()
    {
        $d = new DocumentParser(new Document(new Parsedown()));
        $f = "foo:bar\nbaz:bar:bar";
        $s = $d->parseMetadata($f);
    }

    public function testMetadataCantBeParsedFromContent()
    {
        $d = new DocumentParser(new Document(new Parsedown()));
        $file = file_get_contents(__DIR__.'/fixtures/document_04.md');
        $document = $d->parse($file);
        $meta = $document->get();
        $this->assertCount(2, $meta);
        $this->assertSame(array('foo' => 'bar', 'baz' => 'boo'), $meta);
    }

    public function testBuildDocumentCreatesDocumentCorrectly()
    {
        $d = new DocumentParser(new Document(new Parsedown()));
        $c = 'foo bar';
        $m = array('foo' => 'bar');
        $a = $d->buildDocument($c, $m);
        $this->assertTrue($a instanceof Document);
        $this->assertEquals('foo bar', $a->getContent());
        $this->assertEquals('bar', $a->get('foo'));
    }

    public function testParseDocumentFromMarkdownWithMetadata()
    {
        $d = new DocumentParser(new Document(new Parsedown()));
        $f = file_get_contents(__DIR__.'/fixtures/document_03.md');
        $a = $d->parse($f);
        $this->assertTrue($a instanceof Document);
    }

    public function testParseDocumentWithCorrectContent()
    {
        $d = new DocumentParser(new Document(new Parsedown()));
        $f = file_get_contents(__DIR__.'/fixtures/document_03.md');
        $a = $d->parse($f);
        $this->assertEquals('foo bar baz', $a->getContent());
    }

    public function testParseDocumentWithCorrectMetadata()
    {
        $d = new DocumentParser(new Document(new Parsedown()));
        $f = file_get_contents(__DIR__.'/fixtures/document_03.md');
        $a = $d->parse($f);
        $this->assertCount(2, $a->get());
        $this->assertEquals(array('foo' => 'bar', 'baz' => 'boo'), $a->get());
        $this->assertEquals('bar', $a->get('foo'));
        $this->assertEquals('boo', $a->get('baz'));
    }
}
