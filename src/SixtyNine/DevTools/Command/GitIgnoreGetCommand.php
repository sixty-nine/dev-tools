<?php

namespace SixtyNine\DevTools\Command;

use SixtyNine\DevTools\Builder\GitIgnoreBuilder;
use SixtyNine\DevTools\Builder\VirtualHostBuilder;
use SixtyNine\DevTools\Model\VirtualHost;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class GitIgnoreGetCommand extends Command
{
    /** {@inheritdoc} */
    protected function configure() {
        $this
            ->setName('gen:gi')
            ->setDescription('Get a .gitignore file from gitignore.io')
            ->addArgument('key', InputArgument::REQUIRED, 'The gitignore.io key')
        ;
    }

    /** {@inheritdoc} */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $builder = new GitIgnoreBuilder();

        $res = $builder->getTemplate(explode(',', $input->getArgument('key')));
        $output->writeln('<comment>' . $res . '</comment>');
    }
}
