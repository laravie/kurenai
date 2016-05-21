<?php

namespace Kurenai\Contracts;

interface Document
{
    /**
     * Set the document content in Markdown format.
     *
     * @param  string $content
     *
     * @return \Kurenai\Document
     */
    public function setContent($content);

    /**
     * Get the document content in Markdown format.
     *
     * @return string
     */
    public function getContent();

    /**
     * Get the document content in HTML format.
     *
     * @return string
     */
    public function getHtmlContent();

    /**
     * Set the document metadata using an array.
     *
     * @param  array $metadata
     *
     * @return \Kurenai\Document
     */
    public function set(array $metadata);

    /**
     * Add a piece of metadata to the document.
     *
     * @param  string $key
     * @param  mixed $value
     *
     * @return \Kurenai\Document
     */
    public function add($key, $value);

    /**
     * Get metadata from the document.
     *
     * @param  string $key
     *
     * @return mixed
     */
    public function get($key = null);
}
