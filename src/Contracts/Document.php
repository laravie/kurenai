<?php

namespace Kurenai\Contracts;

interface Document
{
    /**
     * Set the document content in Markdown format.
     *
     * @param  string  $content
     *
     * @return $this
     */
    public function setContent(string $content);

    /**
     * Get the document content in Markdown format.
     *
     * @return string
     */
    public function getContent(): string;

    /**
     * Get the document content in HTML format.
     *
     * @return string
     */
    public function getHtmlContent(): string;

    /**
     * Set the document metadata using an array.
     *
     * @param  array  $metadata
     *
     * @return $this
     */
    public function set(array $metadata);

    /**
     * Add a piece of metadata to the document.
     *
     * @param  string  $key
     * @param  mixed  $value
     *
     * @return $this
     */
    public function add(string $key, $value);

    /**
     * Get metadata from the document.
     *
     * @param  string|null  $key
     *
     * @return mixed
     */
    public function get(?string $key = null);
}
