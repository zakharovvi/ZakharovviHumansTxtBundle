<?php
namespace Zakharovvi\HumansTxtBundle\Tests\Authors\Provider;

use Zakharovvi\HumansTxtBundle\Authors\Provider\GitProvider;
use Zakharovvi\HumansTxtBundle\Authors\Author;

/**
 * @author Vitaliy Zakharov <zakharovvi@gmail.com>
 */
class GitProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $processBuilderMock;

    /**
     * @var string
     */
    private $workspace;

    protected function setUp()
    {
        $this->processBuilderMock = $this->getMock('Symfony\Component\Process\ProcessBuilder');

        $this->workspace = sys_get_temp_dir().
            DIRECTORY_SEPARATOR.
            'ZakharovviHumansTxtBundleTests'.
            DIRECTORY_SEPARATOR.
            time().
            rand(0, 1000);
        mkdir($this->workspace, 0777, true);
    }

    private function addStubsToProcessBuilderMock()
    {
        $this->processBuilderMock
            ->expects($this->at(0))
            ->method('setWorkingDirectory')
            ->with($this->workspace)
            ->will($this->returnSelf());

        $this->processBuilderMock
            ->expects($this->at(1))
            ->method('add')
            ->with('git log')
            ->will($this->returnSelf());
    }

    public function tearDown()
    {
        $this->clean($this->workspace);
    }

    /**
     * @link https://github.com/symfony/Filesystem/blob/master/Tests/FilesystemTest.php
     * @param string $file
     */
    private function clean($file)
    {
        if (is_dir($file) && !is_link($file)) {
            $dir = new \FilesystemIterator($file);
            foreach ($dir as $childFile) {
                $this->clean($childFile);
            }
            rmdir($file);
        } else {
            unlink($file);
        }
    }
    /**
     * @dataProvider invalidProjectDirProvider
     */
    public function testInvalidProjectDir($invalidProjectDir)
    {
        $this->setExpectedException(
            '\InvalidArgumentException',
            "$invalidProjectDir is not a directory"
        );
        new GitProvider($invalidProjectDir, $this->processBuilderMock);
    }

    public function invalidProjectDirProvider()
    {
        return  array(
          array('/qwertyuiop'),
          array('c:\qwertyuiop'),
        );
    }

    public function testProcessBuilderInConstructor()
    {
        $this->addStubsToProcessBuilderMock();
        $this->processBuilderMock
            ->expects($this->at(2))
            ->method('getProcess')
            ->will($this->returnSelf());

        new GitProvider($this->workspace, $this->processBuilderMock);
    }

    public function testProcessErrorInGetAuthors()
    {
        //Process
        $processStub = $this->getMock('Symfony\Component\Process\Process',array(),array(),'',false);
        $processStub->expects($this->at(0))
            ->method('run')
            ->will($this->returnValue(1));
        $sampleErrorMessage = 'sample error message';
        $processStub->expects($this->at(1))
            ->method('getErrorOutput')
            ->will($this->returnValue($sampleErrorMessage));
        //ProcessBuilder
        $this->addStubsToProcessBuilderMock();
        $this->processBuilderMock
            ->expects($this->at(2))
            ->method('getProcess')
            ->will($this->returnValue($processStub));

        $this->setExpectedException(
            '\RuntimeException',
            $sampleErrorMessage
        );
        $gitProvider = new GitProvider($this->workspace, $this->processBuilderMock);
        $gitProvider->getAuthors();
    }

    public function testGetAuthors()
    {
        $gitLog = '
commit b878a87dc4a6ebc2f00cfd0eb7959b4ac9033bd3
Author: Vitaliy Zakharov <zakharovvi@gmail.com>
Date:   Fri Jul 20 03:47:29 2012 +0600

    добавил в composer.json:
            "mopa/bootstrap-bundle": "dev-master",
            "knplabs/knp-paginator-bundle": "dev-master",
            "knplabs/knp-menu-bundle": "dev-master",
            "elnur/blowfish-password-encoder-bundle": "dev-master",
            "doctrine/doctrine-migrations-bundle": "dev-master",
            "doctrine/doctrine-fixtures-bundle": "dev-master"

commit 50ce2f593c3c69d1d3d3982af65a06137db759d0
Merge: 12ba76b 7b3f4ae
Author: Fabien Potencier <fabien.potencier@gmail.com>
Date:   Wed Jul 18 08:01:26 2012 +0200

    merged branch Tobion/patch-5 (PR #365)

    Commits
    -------

    7b3f4ae fix typo in config name

    Discussion
    ----------

    fix typo in config name

commit 12ba76bbd86b24416c5e75728f434a8b6e44c2cc
Merge: 257957b 1d2e1da
Author: Fabien Potencier <fabien.potencier@gmail.com>
Date:   Wed Jul 18 08:01:10 2012 +0200

    merged branch Tobion/patch-4 (PR #364)

    Commits
    -------

    1d2e1da fix directory name

    Discussion
    ----------

    fix directory name

commit 7b3f4ae67096b70f717372a8c363edbec2642280
Author: Tobias Schultze <webmaster@tubo-world.de>
Date:   Wed Jul 18 05:17:20 2012 +0300

    fix typo in config name

commit 1d2e1da5779d7850252f50d62bcb30e2b3070ecc
Author: Tobias Schultze <webmaster@tubo-world.de>
Date:   Wed Jul 18 04:40:40 2012 +0300

    fix directory name
';
        //Process
        $processStub = $this->getMock('Symfony\Component\Process\Process',array(),array(),'',false);
        $processStub->expects($this->at(0))
            ->method('run')
            ->will($this->returnValue(0));
        $processStub->expects($this->at(1))
            ->method('getOutput')
            ->will($this->returnValue($gitLog));
        //ProcessBuilder
        $this->addStubsToProcessBuilderMock();
        $this->processBuilderMock
            ->expects($this->at(2))
            ->method('getProcess')
            ->will($this->returnValue($processStub));
        //authors
        $author1 = new Author('Vitaliy Zakharov');
        $author1->setEmail('zakharovvi@gmail.com');
        $author2 = new Author('Fabien Potencier');
        $author2->setEmail('fabien.potencier@gmail.com');
        $author3 = new Author('Tobias Schultze');
        $author3->setEmail('webmaster@tubo-world.de');
        $authors = array($author1,$author2,$author3);

        $gitProvider = new GitProvider($this->workspace, $this->processBuilderMock);
        $this->assertEquals($authors,$gitProvider->getAuthors());
    }
}
