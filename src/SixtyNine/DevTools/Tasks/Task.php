<?php

namespace SixtyNine\DevTools\Tasks;

use SixtyNine\DevTools\Environment;

abstract class Task
{
    /** @var Environment */
    protected $env;
    /** @var bool */
    private $isSuccess;

    public function __construct(Environment $env)
    {
        $this->env = $env;
        $this->isSuccess = false;
    }

    /** @return boolean */
    public function isSuccess()
    {
        return $this->isSuccess;
    }

    protected function succeed()
    {
        $this->isSuccess = true;
    }

    protected function fail()
    {
        $this->isSuccess = false;
    }

    public function execute() { }

    public static function create($className, ...$args)
    {
        $task = new $className(...$args);
        if (!is_subclass_of($task, self::class)) {
            throw new \InvalidArgumentException('Invalid class: ' . $className);
        }
        return $task;
    }
}
