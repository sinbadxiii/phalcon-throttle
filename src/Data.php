<?php

declare(strict_types=1);

namespace Sinbadxiii\PhalconThrottle;

class Data
{
    protected $ip;

    protected $route;

    protected $limit;

    protected $time;

    protected $key;

    public function __construct(string $ip, string $route, int $limit = 10, int $time = 60)
    {
        $this->ip = $ip;
        $this->route = $route;
        $this->limit = $limit;
        $this->time = $time;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function getKey()
    {
        if (!$this->key) {
            $this->key = sha1($this->ip.$this->route);
        }

        return $this->key;
    }
}