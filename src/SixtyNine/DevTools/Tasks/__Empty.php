<?php

namespace SixtyNine\DevTools\Tasks;

use SixtyNine\DevTools\Environment;

class __Empty extends Task
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);
    }

    public function execute()
    {
        $this->succeed();
    }
}
