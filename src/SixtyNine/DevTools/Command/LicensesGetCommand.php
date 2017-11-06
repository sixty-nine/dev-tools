<?php

namespace SixtyNine\DevTools\Command;

use SixtyNine\DevTools\Builder\LicensesBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LicensesGetCommand extends Command
{
    /** {@inheritdoc} */
    protected function configure() {
        $this
            ->setName('gen:licenses')
            ->setDescription('Get the available licenses')
            ->addArgument('key', InputArgument::REQUIRED, 'The license key')
        ;
    }

    /** {@inheritdoc} */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $builder = new LicensesBuilder();
        $license = $builder->getLicense($input->getArgument('key'));
        $output->writeln($license);
    }
}
