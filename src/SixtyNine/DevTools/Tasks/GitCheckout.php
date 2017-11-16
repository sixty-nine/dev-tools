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

    public function __construct(Environment $env, Path $path, $url, $checkoutName = '')
    {
        parent::__construct($env, $path, '');
        $this->originalPath = $path;
        $this->url = $url;
        $realPath = $this->env->getResolver()->resolve($path, true);
        $this->path = Path::create($realPath);
        $this->command = sprintf('git clone %s %s', $url, $realPath . '/' . $checkoutName);
    }
}
