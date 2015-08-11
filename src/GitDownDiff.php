<?php namespace Skizu\GitDown;

class GitDownDiff extends GitDown
{
    /**
     * The filter closure for log method
     *
     * @var array|string
     */
    protected $filter = '';

    /**
     * @param $fromCommitHash string
     * @param $toCommitHash string
     * @return GitDiff
     */
    public function getDiff($fromCommitHash, $toCommitHash) {
        $logDiff = $this->repository->getLog("$fromCommitHash..$toCommitHash", $this->filter)->getDiff();

        return new GitDiff($logDiff);
    }

    /**
     * Filter files that are diffed using getLog
     *
     * @param $filter array|string
     */
    public function setFilter($filter) {
        $this->filter = $filter;
    }
}