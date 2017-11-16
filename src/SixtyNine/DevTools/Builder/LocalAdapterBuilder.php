<?php

namespace SixtyNine\DevTools\Builder;

use League\Flysystem\Adapter\Local;

class LocalAdapterBuilder
{
    /** @var array */
    protected $permissions;

    public function __construct()
    {
        $this->permissions = [
            'file' => ['writable' => 0777],
            'dir' => ['writable' => 0755]
        ];
    }

    public function createLocalAdapter($path)
    {
        return new Local($path, LOCK_EX, Local::DISALLOW_LINKS, $this->permissions);
    }
}
