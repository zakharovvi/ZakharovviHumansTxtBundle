<?php
namespace Zakharovvi\HumansTxtBundle\Renderer;

/**
 * @author Vitaliy Zakharov <zakharovvi@gmail.com>
 */
interface RendererInterface
{
    /**
     * @param  array             $authors of @see \Zakharovvi\HumansTxtBundle\Authors\Author
     * @return string
     * @throws \RuntimeException if the template cannot be rendered
     */
    public function render(array $authors);
}
