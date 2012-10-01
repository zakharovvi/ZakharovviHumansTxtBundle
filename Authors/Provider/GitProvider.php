<?php
namespace Zakharovvi\HumansTxtBundle\Authors\Provider;

use Symfony\Component\Process\ProcessBuilder;
use Zakharovvi\HumansTxtBundle\Authors\Author;

/**
 * @author Vitaliy Zakharov <zakharovvi@gmail.com>
 */
class GitProvider implements AuthorsProviderInterface
{
    /**
     * @var \Symfony\Component\Process\Process
     */
    private $process;

    /**
     * @param  string                    $projectRoot
     * @param  ProcessBuilder            $processBuilder
     * @throws \InvalidArgumentException If $projectRoot isn't directory
     */
    public function __construct($projectRoot, ProcessBuilder $processBuilder)
    {
        if (!is_dir($projectRoot)) {
            throw new \InvalidArgumentException("$projectRoot is not a directory");
        }
        $this->process = $processBuilder->setWorkingDirectory($projectRoot)
            ->add('git')
            ->add('log')
            ->getProcess();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthors()
    {
        $gitShortLog = $this->getGitLog();

        return $this->createAuthorsFromGitLog($gitShortLog);
    }

    /**
     * @return string
     * @throws \RuntimeException
     */
    private function getGitLog()
    {
        $exitCode = $this->process->run();
        if (0 !== $exitCode) {
            throw new \RuntimeException($this->process->getErrorOutput());
        }

        return $this->process->getOutput();
    }

    /**
     * @param $gitLog
     * @return array of @see Zakharovvi\HumansTxtBundle\Authors\Author
     */
    private function createAuthorsFromGitLog($gitLog)
    {
        //http://gskinner.com/RegExr/?2rhq7
        $regexpr = '~Author:\s(\w+\s\w+)\s<([a-z0-9!#$%&\'*+/=?^_`{|}\~-]+(?:\.[a-z0-9!#$%&\'*+/=?^_`{|}\~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?)>'.PHP_EOL.'~';
        preg_match_all(
            $regexpr,
            $gitLog,
            $authorsDetails//1=>[names].2=>[emails]
        );
        $emails = array_unique($authorsDetails[2]);
        $names = array_intersect_key($authorsDetails[1],$emails);
        $authors = array();
        foreach ($names as $key => $name) {
           $author = new Author($name);
           $author->setEmail($emails[$key]);
           $authors[] = $author;
        }

        return $authors;
    }
}
