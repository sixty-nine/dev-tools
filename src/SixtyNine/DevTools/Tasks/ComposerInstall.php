<?php

namespace SixtyNine\DevTools\Tasks;

use SixtyNine\DevTools\Environment;
use SixtyNine\DevTools\Model\Path;

class ComposerInstall extends RunProcess
{
    /** @var \SixtyNine\DevTools\Model\Path */
    protected $path;

    public function __construct(Environment $env, Path $path)
    {
        parent::__construct($env, $path, '');
        $this->path = $path;
        $realPath = $this->env->getResolver()->resolve($path, true);
        $this->path = Path::create($realPath);
        $this->command = 'composer install';
    }
}
