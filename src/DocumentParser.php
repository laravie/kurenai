<?php namespace Kurenai;

use Kurenai\Exceptions\TooFewSectionsException;
use Kurenai\Contracts\Document as DocumentContract;

class DocumentParser
{
    /**
     * Pattern to split the fields from the article body.
     */
    const SECTION_SPLITTER = '/\s+-{3,}\s+/';

    /**
     * Pattern to split lines within the header section.
     */
    const META_LINE_SPLITTER = '/\n/';

    /**
     * Pattern to split metadata within the header section.
     */
    const META_SPLITTER = '/:/';

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
        $metadata = [];
        $lines    = preg_split(self::META_LINE_SPLITTER, $source);

        foreach ($lines as $line) {
            $parts = preg_split(self::META_SPLITTER, $line, 2);

            if (count($parts) !== 2) {
                continue;
            }

            $key   = strtolower(trim($parts[0]));
            $value = trim($parts[1]);

            $metadata[$key] = $value;
        }

        return $metadata;
    }
}
