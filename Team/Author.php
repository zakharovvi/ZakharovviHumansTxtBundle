<?php
namespace Zakharovvi\HumansTxtBundle\Team;

/**
 * @author Vitaliy Zakharov <zakharovvi@gmail.com>
 */
class Author
{

    private $name;
    private $email;

    /**
     * @param string $name
     * @throws \InvalidArgumentException If author name is blank or is not a string.
     */
    public function __construct($name)
    {
        if (!$name OR !is_string($name)) {
            throw new \InvalidArgumentException('Author name must be a not null string');
        }
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Author
     * @throws \InvalidArgumentException If email adress is not valid.
     */
    public function setEmail($email)
    {
        if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Author email must be a valid email address');
        }
        $this->email = $email;
        return $this;
    }


}
