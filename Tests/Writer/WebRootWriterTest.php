<?php
namespace Zakharovvi\HumansTxtBundle\Tests\Writer;

use Zakharovvi\HumansTxtBundle\Tests\Filesystem;
use Zakharovvi\HumansTxtBundle\Writer\WebRootWriter;

/**
 * @author Vitaliy Zakharov <zakharovvi@gmail.com>
 */
class WebRootWriterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $workspace;

    /**
     * @var \Zakharovvi\HumansTxtBundle\Tests\Filesystem
     */
    private $filesystem;

    protected function setUp()
    {
        $this->filesystem = new Filesystem();
        $this->workspace = $this->filesystem->getWorkspace();
    }

    public function tearDown()
    {
        $this->filesystem->clean($this->workspace);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage WebRoot argument must be is dir
     * @dataProvider invalidWebRootNameProvider
     */
    public function testInvalidWebRootName($webroot)
    {
        new WebRootWriter($webroot);
    }

    public function invalidWebRootNameProvider()
    {
        return array(
            array('/qwertyuiop'),
            array('c:\qwertyuiop')
        );
    }

    public function testNotWritableWebRoot()
    {
        $notWritableDir = $this->workspace.DIRECTORY_SEPARATOR.'notwritable';
        mkdir($notWritableDir, 0577, true);
        $this->setExpectedException(
            '\Zakharovvi\HumansTxtBundle\Exception\IOException',
            "$notWritableDir is not writable"
        );
        new WebRootWriter($notWritableDir);
    }

    public function testWrite()
    {
        $writer = new WebRootWriter($this->workspace);
        $humansTxtContent = 'qwertyuiop';
        $writer->write($humansTxtContent);
        $humansTxtFileName = $this->workspace.DIRECTORY_SEPARATOR.'humans.txt';
        $this->assertFileExists($humansTxtFileName);
        $this->assertEquals($humansTxtContent, file_get_contents($humansTxtFileName));
    }
}
