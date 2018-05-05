Kurenai
==============

Kurenai is a Markdown document parser which allows for extra metadata to be associated with the document.

[![Build Status](https://travis-ci.org/laravie/kurenai.svg?branch=master)](https://travis-ci.org/laravie/kurenai)
[![Latest Stable Version](https://poser.pugx.org/laravie/kurenai/v/stable)](https://packagist.org/packages/laravie/kurenai)
[![Total Downloads](https://poser.pugx.org/laravie/kurenai/downloads)](https://packagist.org/packages/laravie/kurenai)
[![Latest Unstable Version](https://poser.pugx.org/laravie/kurenai/v/unstable)](https://packagist.org/packages/laravie/kurenai)
[![License](https://poser.pugx.org/laravie/kurenai/license)](https://packagist.org/packages/laravie/kurenai)
[![Coverage Status](https://coveralls.io/repos/github/laravie/kurenai/badge.svg?branch=master)](https://coveralls.io/github/laravie/kurenai?branch=master)

## Introduction

Confused? Let's take a look at how it works.

This is what your documents might look like:

    title: This is my document title.
    slug: this-is-the-slug
    date: 12th December 1984
    -------
    This is my **markdown** content!

and here is how you will parse it with Kurenai :

```php
<?php

// Use the Kurenai document parser.
use Kurenai\Document;
use Kurenai\DocumentParser;
use Kurenai\Parser\Parsedown;

// Load our document source.
$source = file_get_contents('my_document.md');

// Create a new document parser
$parser = new DocumentParser(new Document(new Parsedown));

// Parse the loaded source.
$document = $parser->parse($source);

// To get the document content in raw markdown format..
// This is my **markdown** content!
$rawMarkdown = $document->getContent();

// To get the converted HTML content..
// <p>This is my <strong>markdown</strong> content!</p>
$html = $document->getHtmlContent();

// To access the full array of metadata
// array(
//      'title'     => 'This is my document title.',
//      'slug'      => 'this-is-the-slug',
//      'date'      => '12th December 1984'
// );
$metadata = $document->get();

// To access a piece of metadata by key (default: null)..
// this-is-the-slug
$slug = $document->get('slug');
```

## Origin

Kurenai is a forked project from [daylerees/kurenai](https://github.com/daylerees/kurenai).
