<?php
namespace Zakharovvi\HumansTxtBundle\Renderer;

use \Symfony\Component\Templating\EngineInterface;

/**
 * @author Vitaliy Zakharov <zakharovvi@gmail.com>
 */
class TwigRenderer implements RendererInterface
{
    /**
     * @var \Symfony\Component\Templating\EngineInterface
     */
    private $templateEngine;

    /**
     * @var string
     */
    private $skeletonFileName;

    /**
     * @param \Symfony\Component\Templating\EngineInterface $templateEngine
     * @param string                                        $skeletonFileName
     * @throws \InvalidArgumentException If filename isn't readable
     */
    public function __construct(EngineInterface $templateEngine, $skeletonFileName)
    {
        $this->templateEngine = $templateEngine;
        $this->skeletonFileName = $skeletonFileName;
    }

    /**
     * {@inheritdoc}
     */
    public function render(array $authors)
    {
        return $this->templateEngine->render(
            $this->skeletonFileName,
            array('authors' => $authors)
        );
    }
}
