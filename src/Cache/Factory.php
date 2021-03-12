<?php

declare(strict_types=1);

namespace Sinbadxiii\PhalconThrottle\Cache;

use Sinbadxiii\PhalconThrottle\Data;
use Sinbadxiii\PhalconThrottle\Throttlers\Throttler;
use Psr\SimpleCache\CacheInterface;

class Factory implements FactoryInterface
{
    protected $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function make(Data $data)
    {
        return new Throttler($this->cache->getAdapter(), $data->getKey(), $data->getLimit(), $data->getTime() * 60);
    }

    public function getCache()
    {
        return $this->cache;
    }
}