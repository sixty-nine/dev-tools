<?php

namespace SixtyNine\DevTools\Builder;

use gossi\codegen\generator\utils\Writer;
use SixtyNine\DevTools\Model\VirtualHost;

class VirtualHostBuilder
{
    /** @var \SixtyNine\DevTools\Model\VirtualHost */
    protected $host;

    public function __construct(VirtualHost $host)
    {
        $this->host = $host;
    }

    public function build()
    {
        $writer = new Writer();
        $writer
            ->writeln(sprintf('<VirtualHost %s>', $this->getAddressesString()))
            ->indent()
        ;

        /*
            ServerName SERVER_NAME
            DocumentRoot DOCUMENT_ROOT
            ServerAlias SERVER_ALIAS
        */
        $writer->writeln(sprintf('ServerName %s', $this->host->getServerName()));
        foreach ($this->host->getServerAliases() as $alias) {
            $writer->writeln(sprintf('ServerAlias %s', $alias));
        }
        $writer
            ->writeln(sprintf('DocumentRoot %s', $this->host->getDocumentRoot()))
            ->writeln()
        ;

        /*
            <Directory DOCUMENT_ROOT>
                AllowOverride All
                Require all granted
            </Directory>
         */
        $writer
            ->writeln(sprintf('<Directory %s>', $this->host->getDocumentRoot()))
            ->indent()
            ->writeln('AllowOverride All')
            ->writeln('Require all granted')
            ->outdent()
            ->writeln('</Directory>')
            ->writeln()
        ;

        /*
            ErrorLog /var/log/apache2/SERVER_NAME_error.log
            CustomLog /var/log/apache2/SERVER_NAME_access.log combined
         */
        if ($this->host->getCustomLogs()) {
            $logName = str_replace(['.', ' ', '-'], '_', $this->host->getServerName());
            $writer
                ->writeln(sprintf('ErrorLog /var/log/apache2/%s_error.log', $logName))
                ->writeln(sprintf('CustomLog /var/log/apache2/%s_access.log combined', $logName))
                ->writeln()
            ;
        }

        $writer
            ->outdent()
            ->writeln('</VirtualHost>')
        ;

        return $writer->getContent();
    }

    public function getAddressesString()
    {
        $addresses = $this->host->getAddresses();
        return implode(' ', array_map(
            function ($key, $value) { return $value . ':' . $key; },
            array_keys($addresses), $addresses)
        );
    }
} 