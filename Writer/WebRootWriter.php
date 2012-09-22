<?php
namespace Zakharovvi\HumansTxtBundle\Writer;

use Zakharovvi\HumansTxtBundle\Exception\IOException;

/**
 * @author Vitaliy Zakharov <zakharovvi@gmail.com>
 */
class WebRootWriter implements WriterInterface
{
    /**
     * @var string
     */
    private $humansTxtFileName;

    /**
     * @param string $webroot
     * @throws \InvalidArgumentException                         If $webroot is not a dir.
     * @throws \Zakharovvi\HumansTxtBundle\Exception\IOException If humans.txt file in $webroot is not writable.
     */
    public function __construct($webroot)
    {
        if (!is_dir($webroot)) {
            throw new \InvalidArgumentException('WebRoot argument must be is dir');
        }
        if (!is_writable($webroot)) {
            throw new IOException("$webroot is not writable");
        }
        $this->humansTxtFileName = $webroot.DIRECTORY_SEPARATOR.'humans.txt';
    }

    /**
     * {@inheritdoc}
     */
    public function write($humansTxtContent)
    {
        return (bool)file_put_contents($this->humansTxtFileName,$humansTxtContent);
    }
}
