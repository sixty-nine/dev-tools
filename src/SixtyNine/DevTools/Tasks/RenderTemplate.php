<?php

namespace SixtyNine\DevTools\Tasks;

use SixtyNine\DevTools\Environment;
use SixtyNine\DevTools\Model\File;
use SixtyNine\DevTools\Model\Path;
use SixtyNine\DevTools\Templates\Engine;

class RenderTemplate extends Task
{
    /** @var Engine */
    protected $templatesEngine;

    public function __construct(Environment $env, $template, Path $destination, $params = [])
    {
        parent::__construct($env);
        $this->template = $template;
        $this->destination = $destination;
        $this->params = $params;
        $this->templatesEngine = new Engine($env);
    }

    public function execute()
    {
        $content = $this->templatesEngine->render($this->template, $this->params);

        $task = new CreateFile($this->env, File::create($this->destination->getPath(), $content));
        $task->execute();
        $this->succeed();
    }
}
