<?php namespace Skizu\GitDown;

use Gitonomy\Git\Repository;
use League\CommonMark\CommonMarkConverter;
use Gitonomy\Git\Exception\InvalidArgumentException as GitInvalidArgumentException;
use Gitonomy\Git\Exception\ReferenceNotFoundException as GitReferenceNotFoundException;
use Gitonomy\Git\Exception\RuntimeException as GitRuntimeException;
use Skizu\GitDown\Exception\InvalidArgumentException;
use Skizu\GitDown\Exception\ReferenceNotFoundException;
use Skizu\GitDown\Exception\RuntimeException;

abstract class GitDown
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
}