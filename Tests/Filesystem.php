<?php
namespace Zakharovvi\HumansTxtBundle\Tests;

/**
 * @author Vitaliy Zakharov <zakharovvi@gmail.com>
 */
class Filesystem
{
    /**
     * @var string
     */
    private $workspace;

    public function __construct()
    {
        $this->workspace =
            sys_get_temp_dir().
                DIRECTORY_SEPARATOR.
                'ZakharovviHumansTxtBundleTests'.
                DIRECTORY_SEPARATOR.
                time().
                rand(0, 1000);
        mkdir($this->workspace, 0777, true);
    }

    /**
     * @return string
     */
    public function getWorkspace()
    {
            return $this->workspace;
    }

    /**
     * @link https://github.com/symfony/Filesystem/blob/master/Tests/FilesystemTest.php
     * @param string $file
     */
    public function clean($file)
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
}
