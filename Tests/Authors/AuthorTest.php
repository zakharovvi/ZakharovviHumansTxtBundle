<?php
namespace Zakharovvi\HumansTxtBundle\Tests\Authors;

use Zakharovvi\HumansTxtBundle\Authors\Author;

/**
 * @author Vitaliy Zakharov <zakharovvi@gmail.com>
 */
class AuthorTest extends \PHPUnit_Framework_TestCase
{

    public function testGettersAndSetters()
    {
        $name = 'Vitaliy Zakharov';
        $email = 'zakharovvi@gmail.com';
        $author = new Author($name);
        $this->assertEquals($name, $author->getName());

        $this->assertNull($author->getEmail());
        $author->setEmail($email);
        $this->assertEquals($email, $author->getEmail());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @dataProvider invalidNameProvider
     */
    public function testInvalidName($invalidName)
    {
        new Author($invalidName);
    }

     /**
     * @expectedException \InvalidArgumentException
     * @dataProvider invalidEmailProvider
     */
    public function testInvalidEmail($invalidEmail)
    {
        $author = new Author('validname');
        $author->setEmail($invalidEmail);
    }

    public function invalidNameProvider()
    {
        return array(
            array(null),
            array(12.00),
            array(new \DateTime),
            array(''),
        );
    }

    public function invalidEmailProvider()
    {
        return array(
            array(null),
            array('ffafавыппып@@'),
            array('fsdf@gmail'),
            array(''),
        );
    }
}
