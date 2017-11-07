<?php

namespace SixtyNine\DevTools\Tests;

use PHPUnit\Framework\TestCase;
use SixtyNine\DevTools\Builder\VirtualHostBuilder;
use SixtyNine\DevTools\Model\VirtualHost;

class VirtualHostBuilderTest extends TestCase
{
    public function testGetAddressesString()
    {
        $host = new VirtualHost('server', 'root');
        $builder = new VirtualHostBuilder($host);
        $this->assertEquals('*:80', $builder->getAddressesString());

        $host = new VirtualHost('server', 'root', false, [],  [80 => 'domain.tld']);
        $builder = new VirtualHostBuilder($host);
        $this->assertEquals('domain.tld:80', $builder->getAddressesString());

        $host = new VirtualHost('server', 'root', false, [],  [80 => 'domain.tld', 8080 => 'domain2.tld2']);
        $builder = new VirtualHostBuilder($host);
        $this->assertEquals('domain.tld:80 domain2.tld2:8080', $builder->getAddressesString());
    }

    public function testBuild()
    {
        $expected = <<<EOF
<VirtualHost *:80>
	ServerName server
	DocumentRoot root

	<Directory root>
		AllowOverride All
		Require all granted
	</Directory>

</VirtualHost>

EOF;

        $host = new VirtualHost('server', 'root');
        $builder = new VirtualHostBuilder($host);
        $this->assertEquals($expected, $builder->build());
    }

    public function testBuildFull()
    {
        $expected = <<<EOF
<VirtualHost *:80>
	ServerName server
	ServerAlias alias1
	ServerAlias alias2
	DocumentRoot root

	<Directory root>
		AllowOverride All
		Require all granted
	</Directory>

	ErrorLog /var/log/apache2/server_error.log
	CustomLog /var/log/apache2/server_access.log combined

</VirtualHost>

EOF;

        $host = new VirtualHost('server', 'root', true, ['alias1', 'alias2']);
        $builder = new VirtualHostBuilder($host);
        $this->assertEquals($expected, $builder->build());
    }
}