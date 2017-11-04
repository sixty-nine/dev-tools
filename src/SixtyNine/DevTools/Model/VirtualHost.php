<?php

namespace SixtyNine\DevTools\Model;

class VirtualHost
{
    /** @var array */
    protected $addresses;
    /** @var  string */
    protected $serverName;
    /** @var  string */
    protected $documentRoot;
    /** @var array */
    protected $serverAliases = [];
    /** @var  boolean */
    protected $customLogs;

    public function __construct($serverName, $documentRoot, $customLogs = false, array $aliases = [], array $addresses = [80 => '*'])
    {
        $this->serverName = $serverName;
        $this->documentRoot = $documentRoot;
        $this->addresses = $addresses;
        $this->customLogs = $customLogs;
        $this->addresses = $addresses;
        $this->serverAliases = $aliases;
    }

    /** @return string */
    public function getCustomLogs()
    {
        return $this->customLogs;
    }

    /** @return string */
    public function getServerName()
    {
        return $this->serverName;
    }

    /** @return string */
    public function getDocumentRoot()
    {
        return $this->documentRoot;
    }

    /** @return array */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /** @return array */
    public function getServerAliases()
    {
        return $this->serverAliases;
    }
}
