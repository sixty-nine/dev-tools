<?php

namespace SixtyNine\DevTools\Command;

use SixtyNine\DevTools\Builder\LicensesBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LicensesListCommand extends Command
{
    /** {@inheritdoc} */
    protected function configure() {
        $this
            ->setName('gen:licenses:list')
            ->setDescription('Get the available licenses')
        ;
    }

    /** {@inheritdoc} */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $builder = new LicensesBuilder();
        $list = $builder->getList();

        $res = implode(PHP_EOL, array_map(
            function ($value) { return '<info>' . $value->key . '</info>'; },
            $list
        ));

        $output->writeln($res);
    }
}
