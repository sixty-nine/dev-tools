<?php

namespace SixtyNine\DevTools\Builder;

use GuzzleHttp\Client;

class LicensesBuilder
{
    /** @var \GuzzleHttp\Client */
    protected $client;
    /** @var array */
    protected $licenses;
    /** @var array */
    protected $keys;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'https://api.github.com/']);
    }

    public function getList($force = false)
    {
        if ($force || !$this->licenses) {
            /** @var \GuzzleHttp\Psr7\Response $response */
            $response = $this->client->request('GET', 'licenses', ['headers' => ['Accept' => 'application/vnd.github.drax-preview+json']]);
            $this->licenses = json_decode($response->getBody()->getContents());
            $this->keys = array_map(function ($value) {
                return  $value->key;
            }, $this->licenses);
        }

        return $this->licenses;
    }

    public function getKeys($force = false)
    {
        $this->getList($force);
        return $this->keys;
    }

    public function getLicense($key)
    {
        if (!in_array($key, $this->getKeys())) {
            throw new \InvalidArgumentException('Key not found: ' . $key);
        }

        /** @var \GuzzleHttp\Psr7\Response $response */
        $response = $this->client->request('GET', 'licenses/' . $key, ['headers' => ['Accept' => 'application/vnd.github.drax-preview+json']]);
        $license = json_decode($response->getBody()->getContents());
        return $license->body;
    }
}
