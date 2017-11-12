<?php

namespace SixtyNine\DevTools\Tasks;

use SixtyNine\DevTools\Environment;
use SixtyNine\DevTools\Model\Path;

class CreateDir extends Task
{
    /** @var \SixtyNine\DevTools\Model\Path */
    protected $path;

    public function __construct(Environment $env, Path $path)
    {
        parent::__construct($env);
        $this->path = $path;
    }

    public function execute()
    {
        $filename = $this->env->getResolver()->resolve($this->path, false);
        $this->env->getIo()->writeln(sprintf('Create directory <info>%s</info>', $filename));
        if (!$this->env->isDryRun()) {
            $this->env->getFs()->createDir($filename);
        }

        $this->succeed();
    }
}
