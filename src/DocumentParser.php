<?php

namespace Kurenai;

use Kurenai\Contracts\Document;
use Symfony\Component\Yaml\Yaml;
use Kurenai\Exceptions\TooFewSectionsException;
use Symfony\Component\Yaml\Exception\ParseException;

class DocumentParser
{
    /**
     * Pattern to split the fields from the article body.
     */
    const SECTION_SPLITTER = '/\s+-{3,}\s+/';

    /**
     * Document object resolver.
     *
     * @var \Kurenai\Contracts\Document
     */
    protected $documentResolver;

    /**
     * Instantiate an instance optionally passing in a Documemt object resolver.
     *
     * @param  \Kurenai\Contracts\Document  $documentResolver
     */
    public function __construct(Contracts\Document $documentResolver)
    {
        $this->documentResolver = $documentResolver;
    }

    /**
     * Parse a markdown document with metadata.
     *
     * @param  string  $source
     *
     * @return \Kurenai\Contracts\Document
     */
    public function parse(string $source): Contracts\Document
    {
        $content  = $this->parseContent($source);
        $metadata = $this->parseMetadata($this->parseHeader($source));

        return $this->buildDocument($content, $metadata);
    }

    /**
     * Build a Document from the parsed data.
     *
     * @param  string  $content
     * @param  array   $metadata
     *
     * @return \Kurenai\Contracts\Document
     */
    public function buildDocument(string $content, array $metadata): Contracts\Document
    {
        $document = $this->documentResolver;
        $document->setContent($content);
        $document->set($metadata);

        return $document;
    }

    /**
     * Parse the header section of the document.
     *
     * @param  string $source
     *
     * @return string
     */
    public function parseHeader(string $source): string
    {
        return $this->parseSection($source, 0);
    }

    /**
     * Parse the content section of the document.
     *
     * @param  string $source
     *
     * @return string
     */
    public function parseContent(string $source): string
    {
        return $this->parseSection($source, 1);
    }

    /**
     * Parse a dection of the document.
     *
     * @param  string  $source
     * @param  int  $offset
     *
     * @throws \Kurenai\Exceptions\TooFewSectionsException
     *
     * @return string
     */
    public function parseSection(string $source, int $offset): string
    {
        $sections = preg_split(self::SECTION_SPLITTER, $source, 2);

        if (count($sections) != 2) {
            throw new TooFewSectionsException();
        }

        return trim($sections[$offset]);
    }

    /**
     * Parse metadata into an array.
     *
     * @param  string  $source
     *
     * @return array
     */
    public function parseMetadata(string $source): array
    {
        $yaml = Yaml::parse($source);

        if (! is_array($yaml)) {
            throw new ParseException('The YAML value does not appear to be valid UTF-8.', -1, null, null);
        }

        return $yaml;
    }
}
