<?php
namespace Zakharovvi\HumansTxtBundle\Tests\Renderer;

use Zakharovvi\HumansTxtBundle\Tests\Filesystem;
use Zakharovvi\HumansTxtBundle\Renderer\TwigRenderer;
use Zakharovvi\HumansTxtBundle\Authors\Author;

/**
 * @author Vitaliy Zakharov <zakharovvi@gmail.com>
 */
class TwigRendererTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $fileLocatorStub;

    /**
     * @var \Zakharovvi\HumansTxtBundle\Tests\Filesystem
     */
    protected $filesystem;

    /**
     * @var string
     */
    protected $workspace;

    protected function setUp()
    {
        $this->fileLocatorStub = $this->getMock('\Symfony\Component\Config\FileLocatorInterface');
        $this->filesystem = new Filesystem();
        $this->workspace = $this->filesystem->getWorkspace();
    }

    protected function tearDown()
    {
        $this->filesystem->clean($this->workspace);
    }

    /**
     * @param  string $content
     * @return string
     */
    protected function initSkeletonFile($content)
    {
        $realSkeletonFile = $this->workspace.DIRECTORY_SEPARATOR.'humans.txt';
        file_put_contents($realSkeletonFile, $content);
        $unrealSkeletonFile = '/unrealFile';
        $this->fileLocatorStub->expects($this->once())
            ->method('locate')
            ->with($unrealSkeletonFile)
            ->will($this->returnValue($realSkeletonFile));

        return $unrealSkeletonFile;
    }

    public function testFailOnInvalidSkeletonFile()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $nonexistentFile = '/path/to/nonexistent/file';
        $this->fileLocatorStub->expects($this->once())
            ->method('locate')
            ->with($nonexistentFile)
            ->will($this->throwException(new \InvalidArgumentException));
        new TwigRenderer($this->fileLocatorStub, $nonexistentFile);
    }

    public function testFailOnInvalidTemplate()
    {
        $this->setExpectedException('\RuntimeException');
        $skeletonFile = $this->initSkeletonFile('{{ nonexistent_variable }}');
        $renderer = new TwigRenderer($this->fileLocatorStub, $skeletonFile);
        $renderer->render(array());
    }

    public function testRender()
    {
        $template = '/* TEAM */
{% for author in authors %}
    {{ author.name }}
    {{ author.email }}

{% endfor %}
';
        $skeletonFile = $this->initSkeletonFile($template);
        $renderer = new TwigRenderer($this->fileLocatorStub, $skeletonFile);
        $renderedContent = '/* TEAM */
    Vitaliy Zakharov
    zakharovvi@gmail.com

    Noreal Name
    name@example.com

';
        $author1 = new Author('Vitaliy Zakharov');
        $author1->setEmail('zakharovvi@gmail.com');
        $author2 = new Author('Noreal Name');
        $author2->setEmail('name@example.com');
        $authors = array($author1,$author2);
        $this->assertEquals($renderedContent,$renderer->render($authors));
    }
}
