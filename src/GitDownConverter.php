<?php namespace Skizu\GitDown;

use Gitonomy\Git\Tree;
use Gitonomy\Git\Exception\InvalidArgumentException as GitInvalidArgumentException;
use Gitonomy\Git\Exception\ReferenceNotFoundException as GitReferenceNotFoundException;
use Skizu\GitDown\Exception\InvalidArgumentException;
use Skizu\GitDown\Exception\ReferenceNotFoundException;

class GitDownConverter extends GitDown
{
    /**
     * @param $fileName
     * @return string
     */
    public function convertToHTML($fileName)
    {
        return $this->converter->convertToHtml($this->getFile($fileName)->getContent());
    }

    /**
     * @param $fileName string
     * @return \Gitonomy\Git\Blob
     */
    private function getFile($fileName)
    {
        try {
            return $this->getEntry($this->commit->getTree(), $fileName);
        } catch (GitReferenceNotFoundException $e) {
            throw new ReferenceNotFoundException($e->getMessage());
        } catch (GitInvalidArgumentException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }
    }

    /**
     * @param $tree Tree
     * @param $filePath
     * @return mixed
     */
    private function getEntry(Tree $tree, $filePath)
    {
        $entries = array_filter(explode('/', $filePath));

        foreach ($entries as $entry) {
            /** @var \Gitonomy\Git\Tree $tree */
            $tree = $tree->getEntry($entry);
        }

        return $tree;
    }
}