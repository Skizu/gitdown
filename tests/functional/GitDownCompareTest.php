<?php namespace Skizu\GitDown;

class GitDownCompareTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GitDownDiff
     */
    protected $gitDownDiff;

    protected function setUp()
    {
        $this->gitDownDiff = new GitDownDiff(__DIR__ . '/../..');
    }

    public function testBuild_GitDiff_MatchesExpected()
    {
        $diff = $this->gitDownDiff->getDiff('6f89ec3cec587c4e734c9c411eb93d0b4b004b56',
            '210d1534eb4c0c512b1d5d3afab64d14d6e924c8');

        $expected = file_get_contents(__DIR__ . '/../samples/diff.json');

        $this->assertEquals(json_decode($expected, true), $diff->build());
    }

    public function testBuild_GitDiffLineOverrides_MatchesExpected()
    {
        $diff = $this->gitDownDiff->getDiff('6f89ec3cec587c4e734c9c411eb93d0b4b004b56',
            '210d1534eb4c0c512b1d5d3afab64d14d6e924c8');

        $diff->renderAddLine(function ($line) {
            return '<span class="add">' . $line . '</span>';
        });

        $diff->renderRemoveLine(function ($line) {
            return '<span class="removed">' . $line . '</span>';
        });

        $diff->renderContextLine(function ($line) {
            return '<span>' . $line . '</span>';
        });

        $expected = file_get_contents(__DIR__ . '/../samples/htmldiff.json');

        $this->assertEquals(json_decode($expected, true), $diff->build());
    }
}