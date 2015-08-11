# skizu/gitdown

**skizu/gitdown** combines git repos with a Markdown parser for PHP which supports the full [CommonMark] spec

## Goals

* Fully support the [CommonMark spec] (100% compliance)
* Provide an extensible git library integration component
* To be able to load, compare and parse versions of MarkDown files within a [git] repo   

## Installation

This project can be installed via [Composer]:

``` bash
$ composer require skizu/gitdown
```

## Basic Usage

The `GitDownConverter` class provides a simple wrapper for converting [CommonMark] files within a [git] repo to HTML:

```php
use Skizu\GitDown\GitDownConverter;

$gitDownConverter = new GitDownConverter('/path/to/repo');
echo $gitDownConverter->convertToHtml('path/to/file.md');

// <h1>Hello World!</h1>
```

The `GitDownDiff` class provides a simple wrapper for performing diffs on [CommonMark] files within a [git] repo:
 
```php
use Skizu\GitDown\GitDownDiff
 
$gitDownDiff = new GitDownDiff('/path/to/repo');
$diff = $gitDownDiff->getDiff('master@{2 days ago}..master');
 
foreach($diff as $file => $data) {
   echo '<h1>'.$file.'<h1>';
   foreach($data['lines'] as $line) {
      echo $line;
   }
}
 
// <h1>README.md</h1>
// -* Fully support the CommonMark spec (100% compliance)\n
// +* Fully support the [CommonMark spec] (100% compliance)\n 
```

[CommonMark]: http://commonmark.org/
[CommonMark spec]: http://spec.commonmark.org/
[Composer]: https://getcomposer.org/
[git]: https://git-scm.com/