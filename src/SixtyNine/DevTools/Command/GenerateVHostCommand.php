<?php

namespace SixtyNine\DevTools\Command;

use SixtyNine\DevTools\Builder\VirtualHostBuilder;
use SixtyNine\DevTools\Model\VirtualHost;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class GenerateVHostCommand extends Command
{
    /** {@inheritdoc} */
    protected function configure() {
        $this
            ->setName('gen:vhost')
            ->setDescription('Generate a virtual host for Apache 2.4')
            ->addArgument('server-name', InputArgument::REQUIRED, 'The server name')
            ->addArgument('document-root', InputArgument::REQUIRED, 'The document root path')
            ->addOption('custom-logs', null, InputOption::VALUE_NONE, 'Use custom log files')
            ->addOption('alias', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'The server aliases')
        ;
    }

    /** {@inheritdoc} */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $host = new VirtualHost(
            $input->getArgument('server-name'),
            $input->getArgument('document-root'),
            $input->getOption('custom-logs'),
            $input->getOption('alias')
        );
        $builder = new VirtualHostBuilder($host);
        $output->writeln($builder->build());
    }
}
