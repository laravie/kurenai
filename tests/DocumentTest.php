<?php

namespace Kurenai\TestCase;

use Mockery as m;
use Kurenai\Document;
use PHPUnit\Framework\TestCase;
use Kurenai\Contracts\MarkdownParser;

class DocumentTest extends TestCase
{
    public function tearDown(): void
    {
        m::close();
    }

    public function testDocumentCanBeCreated()
    {
        $d = new Document(m::mock('\Kurenai\Contracts\MarkdownParser'));
        $this->assertTrue($d instanceof Document);
    }

    public function testDocumentContentCanBeSet()
    {
        $d = new Document(m::mock('\Kurenai\Contracts\MarkdownParser'));
        $d->setContent('Foo');
        $this->assertEquals('Foo', $d->getContent());
    }

    public function testDocumentMetadataCanBeSet()
    {
        $d = new Document(m::mock('\Kurenai\Contracts\MarkdownParser'));
        $d->set(['Foo' => 'Bar']);
        $this->assertCount(1, $d->get());
        $this->assertEquals('Bar', $d->get('Foo'));
        $this->assertEquals(['Foo' => 'Bar'], $d->get());
    }

    public function testDocumentMetadataCanBeAdded()
    {
        $d = new Document(m::mock('\Kurenai\Contracts\MarkdownParser'));
        $d->add('Foo', 'Bar');
        $this->assertCount(1, $d->get());
        $this->assertEquals('Bar', $d->get('Foo'));
        $this->assertEquals(['Foo' => 'Bar'], $d->get());
        $d->add('Baz', 'Boo');
        $this->assertCount(2, $d->get());
        $this->assertEquals('Bar', $d->get('Foo'));
        $this->assertEquals('Boo', $d->get('Baz'));
        $this->assertEquals(['Foo' => 'Bar', 'Baz' => 'Boo'], $d->get());
    }

    public function testDocumentCanUseCustomParser()
    {
        $document = new Document(new MarkdownParserStub());
        $this->assertEquals('Rendered content.', $document->getHtmlContent());
    }
}

/**
 * This class should be a stub implementation for the MarkdownParserInterface.
 */
class MarkdownParserStub implements MarkdownParser
{
    public function render(?string $content): string
    {
        return 'Rendered content.';
    }
}
