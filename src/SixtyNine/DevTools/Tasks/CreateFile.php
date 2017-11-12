<?php

namespace SixtyNine\DevTools\Tasks;

use SixtyNine\DevTools\Environment;
use SixtyNine\DevTools\Model\File;

class CreateFile extends Task
{
    /** @var \SixtyNine\DevTools\Model\File */
    protected $file;

    public function __construct(Environment $env, File $file)
    {
        parent::__construct($env);
        $this->file = $file;
    }

    public function execute()
    {
        $filename = $this->file->getPath()->getPath();
        if (!$this->file->getOverwrite() && $this->env->getFs()->has($filename)) {
            $this->env->getIo()->writeln('<error>File already exist, no overwrite</error>');
            $this->fail();
            return;
        }

        $this->env->getIo()->writeln(sprintf('Create file <info>%s</info>', $filename));
        if (!$this->env->isDryRun()) {
            $this->env->getFs()->put($filename, $this->file->getContent());
        }

        foreach (explode(PHP_EOL, $this->file->getContent()) as $line) {
            $this->env->getIo()->writeln(sprintf(' |  <code>%s</code>', $line));
        }

        $this->succeed();
    }
}
