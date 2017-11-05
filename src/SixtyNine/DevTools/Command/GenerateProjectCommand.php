<?php

namespace SixtyNine\DevTools\Command;

use League\Flysystem\Adapter\Local;
use SixtyNine\DevTools\Builder\VirtualHostBuilder;
use SixtyNine\DevTools\Builder;
use SixtyNine\DevTools\Model\File;
use SixtyNine\DevTools\Model\Path;
use SixtyNine\DevTools\Model\VirtualHost;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class GenerateProjectCommand extends Command
{
    /** {@inheritdoc} */
    protected function configure() {
        $this
            ->setName('gen:project')
            ->setDescription('Generate project')
            ->addOption('force', null, InputOption::VALUE_NONE, 'If this is not set, run in dry-mode')
        ;
    }

    /** {@inheritdoc} */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $basePath = realpath(__DIR__ . '/../../../../../files');
        $hostBuilder = new VirtualHostBuilder(new VirtualHost('test.lo', $basePath));

        $adapter = new Local(
            $basePath,
            LOCK_EX,
            Local::DISALLOW_LINKS,
            [
                'file' => [
                    'writable' => 0777
                ],
                'dir' => [
                    'writable' => 0755
                ]
            ]
        );
//        $adapter = new Local('/');
        $builder = new Builder($basePath, $adapter, $output, !$input->getOption('force'));
        $builder
            ->createFile(File::create('hello.txt', 'Hello world'))
            ->createFile(File::create('doc/hello.txt', 'Hello world'))
            ->createFile(File::create('/etc/apache/site-available', $hostBuilder->build(), true))
            ->createDirectory(Path::parse('/dev-tools'))
            ->createDirectory(Path::parse('/cache'))
            ->createFile(File::create('/cache/writable', 'yoooo', true))
            ->createFile(File::create('/cache/test/writable', 'yoooo', true))
            ->makeWritable(Path::parse('/cache'))
            ->gitCheckout(Path::parse('/'), 'git@github.com:sixty-nine/dev-tools.git')
        ;

    }
}
