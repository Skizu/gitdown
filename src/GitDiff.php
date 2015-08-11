<?php namespace Skizu\GitDown;


use Closure;
use Gitonomy\Git\Diff\Diff;
use Gitonomy\Git\Diff\File;
use Gitonomy\Git\Diff\FileChange;

class GitDiff
{
    /**
     * @var array
     */
    protected $diffFiles = [];
    public $files = [];

    /**
     * @var Closure
     */
    protected $addLine;
    protected $removeLine;
    protected $contextLine;

    /**
     * @var Diff
     */
    protected $diff;

    /**
     * @param Diff $diff
     */
    public function __construct(Diff $diff)
    {
        $this->diff = $diff;
    }

    /**
     * Override the add line template
     *
     * @param Closure $cb
     */
    public function renderAddLine(Closure $cb)
    {
        $this->addLine = $cb;
    }

    /**
     * Override the remove line template
     *
     * @param Closure $cb
     */
    public function renderRemoveLine(Closure $cb)
    {
        $this->removeLine = $cb;
    }

    /**
     * Override the standard line template
     *
     * @param Closure $cb
     */
    public function renderContextLine(Closure $cb)
    {
        $this->contextLine = $cb;
    }

    /**
     * Build the diff structure using optional modifications
     *
     * @return array
     */
    public function build()
    {
        $this->diffFiles = $this->diff->getFiles();
        $this->addLine = $this->addLine ?: $this->defaultLineAdd();
        $this->removeLine = $this->removeLine ?: $this->defaultLineRemove();
        $this->contextLine = $this->contextLine ?: $this->defaultLineContext();

        /**
         * @var File $file
         * @var FileChange $change
         */
        foreach ($this->diffFiles as $file) {
            $this->files[$file->getName()] = [];

            $changes = $file->getChanges();
            foreach ($changes as $change) {
                $this->files[$file->getNewName()]['names']['old'] = $file->getOldName();
                $this->files[$file->getNewName()]['names']['new'] = $file->getNewName();
                foreach ($change->getLines() as $data) {

                    list ($type, $line) = $data;
                    $this->files[$file->getNewName()]['lines'][] = $this->buildLine($type, $line);
                }
            }
        }

        return $this->files;
    }

    /**
     * Builds line based upon type and registered function
     *
     * @param $type
     * @param $line
     * @return string
     */
    private function buildLine($type, $line)
    {
        if ($type === FileChange::LINE_CONTEXT) {
            return call_user_func($this->contextLine, $line);
        } elseif ($type === FileChange::LINE_ADD) {
            return call_user_func($this->addLine, $line);
        } else {
            return call_user_func($this->removeLine, $line);
        }
    }

    /**
     * Default template for add line
     *
     * @return Closure
     */
    private function defaultLineAdd()
    {
        return function ($line) {
            return '+' . $line . "\n";
        };
    }

    /**
     * Default template for remove line
     *
     * @return Closure
     */
    private function defaultLineRemove()
    {
        return function ($line) {
            return '-' . $line . "\n";
        };
    }

    /**
     * Default template for standard line
     *
     * @return Closure
     */
    private function defaultLineContext()
    {
        return function ($line) {
            return ' ' . $line . "\n";
        };
    }
}