<?php

namespace SixtyNine\DevTools\Templates;

use SixtyNine\DevTools\Environment;

class Engine
{
    /** @var \Twig_Environment */
    protected $twig;
    /** @var \SixtyNine\DevTools\Environment */
    protected $env;

    public function __construct(Environment $env)
    {
        $this->env = $env;
        $this->twig = new \Twig_Environment(new \Twig_Loader_Filesystem($env->getTemplatesPath()), array());
    }

    public function render($templateFile, $params = array())
    {
        $arguments = array_merge($this->getTemplatesParameters(), $params);
        $arguments = array_merge($this->env->getMetadata()->toArray(), $arguments);
        $template = $this->twig->loadTemplate($templateFile);
        return $template->render($arguments);
    }

    protected function getTemplatesParameters()
    {
        return [
            'dataPath' => $this->env->getDataPath(),
            'templatesPath' => $this->env->getTemplatesPath(),
            'basePath' => $this->env->getBasePath(),
        ];
    }
}
