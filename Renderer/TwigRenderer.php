<?php
namespace Zakharovvi\HumansTxtBundle\Renderer;

use Symfony\Component\Config\FileLocatorInterface;

/**
 * @author Vitaliy Zakharov <zakharovvi@gmail.com>
 */
class TwigRenderer implements RendererInterface
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var string
     */
    private $skeletonBaseName;

    /**
     * @param \Symfony\Component\Config\FileLocatorInterface $fileLocator
     * @param string $skeletonFileName
     * @throws \InvalidArgumentException If skeleton file is not found
     */
    public function __construct(FileLocatorInterface $fileLocator, $skeletonFileName)
    {
        $skeletonFileName = $fileLocator->locate($skeletonFileName);
        $this->skeletonBaseName =  basename($skeletonFileName);
        $skeletonBaseDir = dirname($skeletonFileName);
        $this->twig = new \Twig_Environment(new \Twig_Loader_Filesystem($skeletonBaseDir), array(
            'debug'            => true,
            'cache'            => false,
            'strict_variables' => true,
            'autoescape'       => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function render(array $authors)
    {
        try {
            return $this->twig->render($this->skeletonBaseName, array('authors' => $authors));
        } catch (\Twig_Error_Runtime $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }
}
