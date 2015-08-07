<?php namespace Skizu\GitDown;

use Gitonomy\Git\Exception\InvalidArgumentException as GitInvalidArgumentException;
use Gitonomy\Git\Exception\ReferenceNotFoundException as GitReferenceNotFoundException;
use Gitonomy\Git\Exception\RuntimeException as GitRuntimeException;
use Skizu\GitDown\Exception\InvalidArgumentException;
use Skizu\GitDown\Exception\ReferenceNotFoundException;
use Skizu\GitDown\Exception\RuntimeException;
use Gitonomy\Git\Repository;
use League\CommonMark\CommonMarkConverter;

class GitDownConverter
{
    /**
     * The git repository instance.
     *
     * @var \Gitonomy\Git\Repository
     */
    protected $repository;

    /**
     * The MarkDown converter instance.
     *
     * @var \League\CommonMark\Converter
     */
    protected $converter;

    /**
     * The git commit instance.
     *
     * @var \Gitonomy\Git\Commit
     */
    protected $commit;

    /**
     * Create a new GitDown parser instance.
     *
     * @param $directory string
     * @throws RuntimeException if GitRuntimeException caught
     * @throws InvalidArgumentException if GitInvalidArgumentException caught
     */
    public function __construct($directory)
    {
        try {
            $this->converter = new CommonMarkConverter();
            $this->repository = new Repository($directory);
            $this->commit = $this->repository->getHeadCommit();
        } catch (GitInvalidArgumentException $e) {
            throw new InvalidArgumentException($e->getMessage());
        } catch (GitRuntimeException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Set git repo commit id to look up
     *
     * @param $commitHash string
     */
    public function setCommit($commitHash)
    {
        try {
            $this->commit = $this->repository->getCommit($commitHash);
        } catch (GitInvalidArgumentException $e) {
            throw new InvalidArgumentException($e->getMessage());
        } catch (GitRuntimeException $e) {
            throw new RuntimeException($e->getMessage());
        } catch (GitReferenceNotFoundException $e) {
            throw new ReferenceNotFoundException($e->getMessage());
        }
    }

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
            return $this->commit->getTree()->getEntry($fileName);
        } catch (GitReferenceNotFoundException $e) {
            throw new ReferenceNotFoundException($e->getMessage());
        } catch (GitInvalidArgumentException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }
    }

}