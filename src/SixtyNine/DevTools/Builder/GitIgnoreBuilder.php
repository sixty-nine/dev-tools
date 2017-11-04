<?php

namespace SixtyNine\DevTools\Builder;

use GuzzleHttp\Client;

class GitIgnoreBuilder
{
    /** @var \GuzzleHttp\Client */
    protected $client;
    /** @var array */
    protected $codes;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'https://www.gitignore.io/api/']);

    }

    public function getCodes($force = false)
    {
        if ($force || !$this->codes) {
            /** @var \GuzzleHttp\Psr7\Response $response */
            $response = $this->client->request('GET', 'list');
            $this->codes = explode(',', str_replace(PHP_EOL, ',', $response->getBody()->getContents()));
        }

        return $this->codes;
    }

    public function getTemplate($keys)
    {
        $keys = is_array($keys) ? $keys : [$keys];

        foreach ($keys as $key) {
            if (!in_array($key, $this->getCodes())) {
                throw new \InvalidArgumentException('Invalid key: ' . $key);
            }
        }

        /** @var \GuzzleHttp\Psr7\Response $response */
        $response = $this->client->request('GET', implode(',', $keys));
        return $response->getBody()->getContents();
    }
} 