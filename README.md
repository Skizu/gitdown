# skizu/gitdown

**skizu/gitdown** combines git repos with a Markdown parser for PHP which supports the full [CommonMark] spec

## Goals

* Fully support the [CommonMark spec] (100% compliance)
* Provide an extensible git library integration component
* To be able to load, compare and parse versions of MarkDown files within a gitrepo   

## Installation

This project can be installed via [Composer]:

``` bash
$ composer require skizu/gitdown
```

## Basic Usage

The `GitDownConverter` class provides a simple wrapper for converting CommonMark files within a git to HTML:

```php
use Skizu/GitDown/GitDownConverter;

$converter = new Converter('/path/to/repo');
echo $converter->convertToHtml('path/to/file.md');

// <h1>Hello World!</h1>
```

[CommonMark]: http://commonmark.org/
[CommonMark spec]: http://spec.commonmark.org/