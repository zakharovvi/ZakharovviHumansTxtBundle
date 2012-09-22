<?php
namespace Zakharovvi\HumansTxtBundle\Writer;

/**
 * @author Vitaliy Zakharov <zakharovvi@gmail.com>
 */
interface WriterInterface
{
    /**
     * @param string $humansTxtContent
     * @return bool
     */
    public function write($humansTxtContent);
}
