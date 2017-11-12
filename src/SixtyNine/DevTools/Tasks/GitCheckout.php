<?php

namespace SixtyNine\DevTools\Tasks;

use SixtyNine\DevTools\Environment;
use SixtyNine\DevTools\Model\Path;
use Symfony\Component\Process\Process;

class GitCheckout extends RunProcess
{
    /** @var \SixtyNine\DevTools\Model\Path */
    protected $path;
    /** @var \SixtyNine\DevTools\Model\Path */
    protected $originalPath;
    /** @var string */
    protected $url;

    public function __construct(Environment $env, Path $path, $url)
    {
        parent::__construct($env, $path, '');
        $this->originalPath = $path;
        $this->url = $url;
        $realPath = $this->env->getResolver()->resolve($path, true);
        $this->path = Path::create($realPath);
        $this->command = sprintf('git clone %s', $url);
    }

    public function execute()
    {
        parent::execute();

        $gitDir = str_replace('.git', '', basename($this->url));
        $gitDir = sprintf('%s/%s', $this->originalPath->getPath(), $gitDir);
        $composerJson = sprintf('%s/composer.json', $gitDir);
        if ($this->env->getFs()->has($composerJson)) {
            $task = new ComposerInstall($this->env, Path::create($gitDir, true));
            $task->execute();
        }
    }
}
