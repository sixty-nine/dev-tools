<?php

namespace SixtyNine\DevTools;

use Symfony\Component\Console\Application;
use SixtyNine\DevTools\Command;

class App extends Application
{
    public function __construct() {

        parent::__construct('Welcome to Sixty-Nine\'s Dev-Tools', '1.0');

        $commands = array(
            new Command\GenerateVHostCommand(),
            new Command\GenerateProjectCommand(),
            new Command\GitIgnoreListCommand(),
            new Command\GitIgnoreGetCommand(),
            new Command\LicensesListCommand(),
            new Command\LicensesGetCommand(),
        );

        $this->addCommands($commands);
    }
}