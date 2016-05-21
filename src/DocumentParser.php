<?php

namespace Kurenai;

use Symfony\Component\Yaml\Yaml;
use Kurenai\Exceptions\TooFewSectionsException;
use Kurenai\Contracts\Document as DocumentContract;

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
    public function __construct(DocumentContract $documentResolver)
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
    public function parse($source)
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
    public function buildDocument($content, array $metadata)
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
    public function parseHeader($source)
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
    public function parseContent($source)
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
    public function parseSection($source, $offset)
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
    public function parseMetadata($source)
    {
        return Yaml::parse($source);
    }
}
