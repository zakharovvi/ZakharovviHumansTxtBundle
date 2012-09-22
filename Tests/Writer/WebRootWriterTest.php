<?php
namespace Zakharovvi\HumansTxtBundle\Tests\Writer;

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

    protected function setUp()
    {
        $this->workspace = sys_get_temp_dir().DIRECTORY_SEPARATOR.'ZakharovviHumansTxtBundleTests'.DIRECTORY_SEPARATOR.time().rand(0, 1000);
        mkdir($this->workspace, 0777, true);
    }

    public function tearDown()
    {
        $this->clean($this->workspace);
    }

    /**
     * @link https://github.com/symfony/Filesystem/blob/master/Tests/FilesystemTest.php
     * @param string $file
     */
    private function clean($file)
    {
        if (is_dir($file) && !is_link($file)) {
            $dir = new \FilesystemIterator($file);
            foreach ($dir as $childFile) {
                $this->clean($childFile);
            }
            rmdir($file);
        } else {
            unlink($file);
        }
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
        $this->setExpectedException('\Zakharovvi\HumansTxtBundle\Exception\IOException',"$notWritableDir is not writable");
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
