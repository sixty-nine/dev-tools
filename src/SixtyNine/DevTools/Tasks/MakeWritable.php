<?php

namespace SixtyNine\DevTools\Tasks;

use SixtyNine\DevTools\Environment;
use SixtyNine\DevTools\Model\Path;

class MakeWritable extends Task
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

        $this->env->getIo()->writeln(sprintf('Make <info>%s</info> writable', $filename));

        if (!$this->env->isDryRun()) {
            $this->makeWritable($this->path);
        }
        $this->succeed();
    }

    protected function makeWritable(Path $path)
    {
        $filename = $this->env->getResolver()->resolve($path, false);
        $object = $this->env->getFs()->get($filename);
        if ($object->isDir()) {
            foreach ($this->env->getFs()->listContents($filename) as $file) {
                $this->makeWritable(Path::create($file['path'], true));
            }
        }

        try {
            $this->env->getFs()->setVisibility($filename, 'writable');
        } catch (\LogicException $ex) {
            // The filesystem does not support visibility, do nothing...
        }
    }
}
