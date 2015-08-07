<?php namespace Skizu\GitDown;

class GitDownTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GitDownConverter
     */
    protected $gitDown;

    protected function setUp()
    {
        $this->gitDown = new GitDownConverter(__DIR__ . '/../..');
    }

    public function testConvertToHtml_Head_NotEmpty()
    {
        $html = $this->gitDown->convertToHTML('README.md');

        $this->assertNotEmpty($html);
    }

    public function testConvertToHtml_PreviousCommit_FileMatch()
    {
        $this->gitDown->setCommit('6f89ec3cec587c4e734c9c411eb93d0b4b004b56');
        $html = $this->gitDown->convertToHTML('README.md');

        $expected = file_get_contents(__DIR__ . '/../samples/6f89ec3cec587c4e734c9c411eb93d0b4b004b56/README.md');

        $this->assertEquals($expected, $html);
    }

    /**
     * @expectedException \Skizu\GitDown\Exception\InvalidArgumentException
     * @expectedException \Skizu\GitDown\Exception\GitDownExceptionInterface
     */
    public function testGitDownConverter_DirectoryNotFound_ExceptionThrown()
    {
        new GitDownConverter('fake_directory');
    }

    /**
     * @expectedException \Skizu\GitDown\Exception\ReferenceNotFoundException
     * @expectedException \Skizu\GitDown\Exception\GitDownExceptionInterface
     */
    public function testGitDownConverter_CommitNotFound_ExceptionThrown()
    {
        $this->gitDown->setCommit('fake_commit');
    }

    /**
     * @expectedException \Skizu\GitDown\Exception\InvalidArgumentException
     * @expectedException \Skizu\GitDown\Exception\GitDownExceptionInterface
     */
    public function testGitDownConverter_FileNotFound_ExceptionThrown()
    {
        $this->gitDown->convertToHTML('fake_file');
    }
}