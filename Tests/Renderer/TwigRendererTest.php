<?php
namespace Zakharovvi\HumansTxtBundle\Tests\Renderer;

use Zakharovvi\HumansTxtBundle\Renderer\TwigRenderer;

/**
 * @author Vitaliy Zakharov <zakharovvi@gmail.com>
 */
class TwigRendererTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $twigStub;

    private $skeletonFileName = '/path/to/skeletonFile';

    protected function setUp()
    {
         $this->twigStub = $this->getMock('\Symfony\Component\Templating\EngineInterface');
    }

    public function testRender()
    {
        $authors = array();
        $humansTxtContent = 'sample humans.txt content';
        $this->twigStub
            ->expects($this->once())
            ->method('render')
            ->with($this->skeletonFileName, array('authors' => $authors))
            ->will($this->returnValue($humansTxtContent));

        $renderer = new TwigRenderer($this->twigStub, $this->skeletonFileName);
        $this->assertEquals($humansTxtContent, $renderer->render($authors));
    }

}
