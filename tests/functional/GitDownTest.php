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

    public function testConvert_Head_NotEmpty()
    {
        $html = $this->gitDown->convert('README.md');

        $this->assertNotEmpty($html);
    }

    public function testConvert_NestedFile_NotEmpty()
    {
        $html = $this->gitDown->convert('tests/samples/nested.md');

        $this->assertNotEmpty($html);
    }

    public function testConvert_PreviousCommit_FileMatch()
    {
        $this->gitDown->setCommit('6f89ec3cec587c4e734c9c411eb93d0b4b004b56');
        $html = $this->gitDown->convert('README.md');

        $expected = file_get_contents(__DIR__ . '/../samples/6f89ec3cec587c4e734c9c411eb93d0b4b004b56/README.md');

        $this->assertEquals($expected, $html);
    }

    public function testConvert_XMLValidationCallBack_ValidXML()
    {
        $valid = $this->gitDown->convert('phpunit.xml', function($file) {
            $doc = new \DOMDocument();
            $doc->loadXML($file);
            return $doc->schemaValidate(__DIR__ . '/../samples/phpunit.xsd');
        });

        $this->assertEquals(true, $valid);
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
        $this->gitDown->convert('fake_file');
    }

    /**
     * @expectedException \Skizu\GitDown\Exception\InvalidArgumentException
     * @expectedException \Skizu\GitDown\Exception\GitDownExceptionInterface
     */
    public function testGitDownConverter_NestedFileNotFound_ExceptionThrown()
    {
        $this->gitDown->convert('tests/fake_file');
    }
}