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
use SixtyNine\DevTools\Tasks\CreateFile;
use SixtyNine\DevTools\Tasks\Task;
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

        /** @var \SixtyNine\DevTools\Builder\LocalAdapterBuilder $localAdapterBuilder */
        $localAdapterBuilder = $this->container->get('local_adapter_builder');
        $adapter = $localAdapterBuilder->createLocalAdapter($basePath);

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

        $cmd = sprintf(
            'composer init --name="%s/%s" --description="description" ' .
            '--require="symfony/console:3.*" --require="symfony/process:3.*" ' .
            '--require-dev="phpunit/phpunit ^6.4" -n',
            strtolower($metadata->getVendor()->getNamespace()),
            strtolower($metadata->getProject()->getName())
        );
        $env = new Environment($basePath, $adapter, new ConsoleIO($input, $output), $metadata, !$input->getOption('force'));
        $builder = new Builder($env);
        $builder
            ->createDirectory(Path::parse('/src'))
            ->createDirectory(Path::parse('/src/tests'))
            ->createDirectory(Path::parse('/artefacts/doc'))
            ->createDirectory(Path::parse('/artefacts/coverage'))
            ->createFile(File::create('/etc/v-host', $hostBuilder->build(), true))
            ->renderTemplate('files/composer-test-bootstrap.php.twig', Path::parse('/src/tests/bootstrap.php'))
            ->renderTemplate('files/phpunit.xml.twig', Path::parse('/src/tests/phpunit.xml.dist'))
            ->renderTemplate('files/Makefile.twig', Path::parse('/Makefile'))
        ;

        $path = $env->getResolver()->resolve('/src', true);;
        $builder
            ->runProcess($path, $cmd)
            ->composerInstall(Path::parse('/src'))
        ;
    }
}
