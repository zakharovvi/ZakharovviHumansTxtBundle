<?php
namespace Zakharovvi\HumansTxtBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Vitaliy Zakharov <zakharovvi@gmail.com>
 */
class GenerateHumansTxtCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('humans_txt:generate')
            ->setDescription('Generates a humans.txt file in webroot');
    }

    /**
     * @param  \Symfony\Component\Console\Input\InputInterface   $input
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     * @throws \Exception                                        if humans,txt cannot be generated
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $authorsProvider = $this->getContainer()->get('zakharovvi.humans_txt.authors_provider');
        $renderer = $this->getContainer()->get('zakharovvi.humans_txt.renderer');
        $writer = $this->getContainer()->get('zakharovvi.humans_txt.writer');
        $writer->write(
            $renderer->render(
                $authorsProvider->getAuthors()
           )
       );
       $output->writeln('<info>humans.txt was generated in the web folder</info>');
    }

}
