# Dev-Tools

## Generate .gitignore files

The tools can consume the gitignore.io api to generate gitignore files.

To get the list of available gitignore templates:

```bash
bin/dev-tools gen:gi:list
```

An optional search term can be added to narrow down the list.

```bash
bin/dev-tools gen:gi:list php
```

To generate the gitignore file use the `gen:gi` command with the name of one or more templates separated by comas.


```bash
bin/dev-tools gen:gi symfony,phpstorm
```

## Generate virtual host

```bash
bin/dev-tools gen:vhost 
	dev-tools.lo
	/home/dev/dev-tools/src
	--alias=dev-tools.dev
	--alias=tools.dev
	--custom-logs
```

Will output:

```
<VirtualHost *:80>
	ServerName dev-tools.lo
	ServerAlias dev-tools.dev
	ServerAlias tools.dev
	DocumentRoot /home/dev/dev-tools/src

	<Directory /home/dev/dev-tools/src>
		AllowOverride All
		Require all granted
	</Directory>

	ErrorLog /var/log/apache2/test_lo_error.log
	CustomLog /var/log/apache2/test_lo_access.log combined

</VirtualHost>
```
