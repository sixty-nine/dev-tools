<?php

namespace SixtyNine\DevTools;

use SixtyNine\DevTools\Command\ContainerAwareCommand;
use SixtyNine\DevTools\DependencyInjection\ContainerBuilder;
use Symfony\Component\Console\Application;
use SixtyNine\DevTools\Command;

class App extends Application
{
    public function __construct() {

        parent::__construct('Welcome to Sixty-Nine\'s Dev-Tools', '1.0');

        $configFile = __DIR__ . '/Resources/config/config.yml';
        $container = ContainerBuilder::build($configFile);

        $commands = array(
            new Command\GenerateVHostCommand(),
            new Command\GenerateProjectCommand(),
            new Command\CloneProjectCommand(),
            new Command\GitIgnoreListCommand(),
            new Command\GitIgnoreGetCommand(),
            new Command\LicensesListCommand(),
            new Command\LicensesGetCommand(),
        );

        foreach ($commands as $command) {
            if ($command instanceof ContainerAwareCommand) {
                $command->setContainer($container);
            }
        }

        $this->addCommands($commands);
    }
}