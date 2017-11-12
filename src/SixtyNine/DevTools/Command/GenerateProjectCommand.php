<?php

namespace SixtyNine\DevTools\Command;

use League\Flysystem\Adapter\Local;
use SixtyNine\DevTools\Builder\VirtualHostBuilder;
use SixtyNine\DevTools\Builder;
use SixtyNine\DevTools\ConsoleIO;
use SixtyNine\DevTools\Environment;
use SixtyNine\DevTools\Model\File;
use SixtyNine\DevTools\Model\Metadata;
use SixtyNine\DevTools\Model\Path;
use SixtyNine\DevTools\Model\Project;
use SixtyNine\DevTools\Model\Vendor;
use SixtyNine\DevTools\Model\VirtualHost;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;

class GenerateProjectCommand extends ContainerAwareCommand
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
        $formatter = $this->container->get('output_formatter');
        $output->setFormatter($formatter);

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

        $metadata = new Metadata(
            Project::create()
                ->setName('Dev-Tools')
                ->setLicense('MIT')
            ,
            Vendor::create()
                ->setName('Sixty-Nine')
                ->setEmail('hello@sixty-nine.ch')
                ->setNamespace('SixtyNine')
        );

        $env = new Environment($basePath, $adapter, new ConsoleIO($input, $output), $metadata, !$input->getOption('force'));
        $builder = new Builder($env);
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
            ->renderTemplate('files/composer-test-bootstrap.php.twig', Path::parse('/tests/bootstrap.php'))
            ->renderTemplate('files/phpunit.xml.twig', Path::parse('/tests/phpunit.xml.dist'))
            ->renderTemplate('projects/cli-project.yml', Path::parse('/tests/yooo'))
        ;

        $env->getIo()->dumpFile(Path::parse($env->getResolver()->resolve('/hello.txt')));

    }
}
