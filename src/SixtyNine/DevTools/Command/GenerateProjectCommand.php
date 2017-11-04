<?php

namespace SixtyNine\DevTools\Command;

use League\Flysystem\Filesystem as BaseFileSystem;
use League\Flysystem\Adapter\Local;
use SixtyNine\DevTools\Builder\ProjectBuilder;
use SixtyNine\DevTools\Builder\VirtualHostBuilder;
use SixtyNine\DevTools\FileSystem;
use SixtyNine\DevTools\Model\Project;
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
        $builder = new VirtualHostBuilder(new VirtualHost('test.lo', $basePath));

        $project = new Project('Hello world', '/home/dev/hello');
        $project
            ->createFile('hello.txt', 'Hello world')
            ->createFile('doc/hello.txt', 'Hello world')
            ->createFile('/etc/apache/site-available', $builder->build(), true)
            ->createDirectory('/cache')
            ->createFile('/cache/writable', 'yoooo', true)
            ->createFile('/cache/test/writable', 'yoooo', true)
            ->makeWritable('/cache')
        ;

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
        $baseFilesystem = new BaseFileSystem($adapter);
        $filesystem = new FileSystem($baseFilesystem, $output, !$input->getOption('force'));
        $builder = new ProjectBuilder($project, $filesystem);
        $builder->build();
    }
}
