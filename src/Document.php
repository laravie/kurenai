<?php

namespace Kurenai;

class Document implements Contracts\Document
{
    /**
     * The document body in Markdown format.
     *
     * @var string
     */
    protected $content;

    /**
     * An array of document metadata.
     *
     * @var array
     */
    protected $metadata = [];

    /**
     * A Kurenai\MarkdownParserInterface implementation.
     *
     * @var \Kurenai\Contracts\MarkdownParser
     */
    protected $markdownParser;

    /**
     * Instantiate an instance optionally injecting a markdown parser implementation.
     *
     * @param  \Kurenai\Contracts\MarkdownParser  $markdownParser
     */
    public function __construct(Contracts\MarkdownParser $markdownParser)
    {
        $this->markdownParser = $markdownParser;
    }

    /**
     * Set the document content in Markdown format.
     *
     * @param  string  $content
     *
     * @return $this
     */
    public function setContent(string $content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the document content in Markdown format.
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Get the document content in HTML format.
     *
     * @return string
     */
    public function getHtmlContent(): string
    {
        return $this->markdownParser->render($this->content);
    }

    /**
     * Set the document metadata using an array.
     *
     * @param  array  $metadata
     *
     * @return $this
     */
    public function set(array $metadata)
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * Add a piece of metadata to the document.
     *
     * @param  string  $key
     * @param  mixed   $value
     *
     * @return $this
     */
    public function add(string $key, $value)
    {
        $this->metadata[$key] = $value;

        return $this;
    }

    /**
     * Get metadata from the document.
     *
     * @param  string|null  $key
     *
     * @return mixed
     */
    public function get(?string $key = null)
    {
        if (is_null($key)) {
            return $this->metadata;
        }

        if (! array_key_exists($key, $this->metadata)) {
            return null;
        }

        return $this->metadata[$key];
    }
}
