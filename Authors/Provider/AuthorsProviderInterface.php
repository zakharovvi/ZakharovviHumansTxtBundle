<?php

namespace Zakharovvi\HumansTxtBundle\Authors\Provider;

/**
 * @author Vitaliy Zakharov <zakharovvi@gmail.com>
 */
interface AuthorsProviderInterface
{
    /**
     * @return array of @see Zakharovvi\HumansTxtBundle\Authors\Author
     * @throws \Exception
     */
    public function getAuthors();
}
