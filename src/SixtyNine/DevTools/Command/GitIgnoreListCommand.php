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

class GitIgnoreListCommand extends Command
{
    /** {@inheritdoc} */
    protected function configure() {
        $this
            ->setName('gen:gi:list')
            ->setDescription('Get the key of available gitignore.io keys')
            ->addArgument('search', InputArgument::OPTIONAL, 'Search string', false)
        ;
    }

    /** {@inheritdoc} */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $builder = new GitIgnoreBuilder();
        $codes = $builder->getCodes();
        $search = $input->getArgument('search');

        if ($search) {
            $codes = array_filter($codes, function ($value) use ($search) {
                return strpos($value, $search) !== false;
            });
        }

        $res = implode(PHP_EOL, array_map(
            function ($value) { return '<info>' . $value . '</info>'; },
            $codes
        ));

        $output->writeln($res);
    }
}
