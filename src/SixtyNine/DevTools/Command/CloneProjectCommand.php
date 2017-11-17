<?php

namespace SixtyNine\DevTools\Command;

use SixtyNine\DevTools\Builder;
use SixtyNine\DevTools\ConsoleIO;
use SixtyNine\DevTools\Environment;
use SixtyNine\DevTools\Model\Metadata;
use SixtyNine\DevTools\Model\Path;
use SixtyNine\DevTools\Model\Project;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;

class CloneProjectCommand extends ContainerAwareCommand
{
    /** {@inheritdoc} */
    protected function configure() {
        $this
            ->setName('gen:project:checkout')
            ->setDescription('Checkout a project form github')
            ->addArgument('url', InputArgument::REQUIRED, 'Checkout URL')
            ->addOption('force', null, InputOption::VALUE_NONE, 'If this is not set, run in dry-mode')
        ;
    }

    /** {@inheritdoc} */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $formatter = $this->container->get('output_formatter');
        $output->setFormatter($formatter);

        $basePath = realpath(__DIR__ . '/../../../../../files');

        /** @var \SixtyNine\DevTools\Builder\LocalAdapterBuilder $localAdapterBuilder */
        $localAdapterBuilder = $this->container->get('local_adapter_builder');
        $adapter = $localAdapterBuilder->createLocalAdapter($basePath);

        $env = new Environment($basePath, $adapter, new ConsoleIO($input, $output), new Project(), !$input->getOption('force'));
        $builder = new Builder($env);
        $builder
            ->createDirectory(Path::parse('/artefacts/doc'))
            ->createDirectory(Path::parse('/artefacts/coverage'))
            ->gitCheckout(Path::parse('/'), 'git@github.com:sixty-nine/dev-tools.git', 'src')
            ->renderTemplate('files/Makefile.twig', Path::parse('/Makefile'))
        ;

        $composerJson = $env->getResolver()->resolve('/src/composer.json', false);
        if ($env->getFs()->has($composerJson)) {
            $project = Project::fromComposerJson($env->getResolver()->resolve('/src/composer.json', true));
            $env->setProject($project);
            $builder->composerInstall(Path::parse(dirname($composerJson)));
        }
    }
}
