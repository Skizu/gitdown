<?php

namespace Skizu\GitDown\Converters;

use League\CommonMark\CommonMarkConverter;

trait Markdown
{
    public function MarkDownConverter()
    {
        return function ($file) {
            $converter = new CommonMarkConverter();
            return $converter->convertToHTML($file);
        };
    }
}