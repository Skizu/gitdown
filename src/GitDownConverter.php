<?php namespace Skizu\GitDown;

use Closure;


class GitDownConverter extends GitDown
{
    use Converters\Markdown;

    /**
     * @param $fileName
     * @param Closure $cb
     * @return string
     */
    public function convert($fileName, Closure $cb=null)
    {
        $file = $this->getFile($fileName)->getContent();

        if($cb == null)
            $cb = $this->MarkDownConverter();

        return $cb($file);
    }
}