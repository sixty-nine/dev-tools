<?php

namespace SixtyNine\DevTools\Tasks;

use SixtyNine\DevTools\Environment;
use SixtyNine\DevTools\Model\Path;
use Symfony\Component\Process\Process;

class RunProcess extends Task
{
    /** @var \SixtyNine\DevTools\Model\Path */
    protected $path;
    /** @var string */
    protected $command;

    public function __construct(Environment $env, Path $path, $command)
    {
        parent::__construct($env);
        $this->path = $path;
        $this->command = $command;
    }

    public function execute()
    {
        $this->env->getIo()->writeln(sprintf('Running <info>%s</info> in <info>%s</info>', $this->command, $this->path->getPath()));

        if ($this->env->isDryRun()) {
            return;
        }

        $process = new Process($this->command, $this->path->getPath());
        $process->start();

        foreach ($process as $data) {
            $this->env->getIo()->writeln(sprintf('<comment>%s</comment>', $this->normalizeOutput($data)));
        }

        if ($process->isSuccessful()) {
            $this->env->getIo()->writeln('<info>Done</info>');
            $this->succeed();
            return;
        }

        $this->env->getIo()->writeln('<error>Failed</error>');
        $this->fail();
    }

    protected function normalizeOutput($output)
    {
        return substr($output, -1) === PHP_EOL ? substr($output, 0, -1) : $output;
    }
}
