<?php

declare(strict_types=1);

namespace Sinbadxiii\PhalconThrottle\Throttlers;

use Countable;
use Phalcon\Cache\Adapter\AdapterInterface;
use Phalcon\Cache\Adapter\Redis;

class Throttler implements ThrottlerInterface, Countable
{
    protected $store;

    protected $key;

    protected $limit;

    protected $time;

    protected $number;

    public function __construct(AdapterInterface $store, string $key, int $limit, int $time)
    {
        $this->store = $store;
        $this->key = $key;
        $this->limit = $limit;
        $this->time = $time;
    }

    public function attempt()
    {
        $response = $this->check();

        $this->hit();

        return $response;
    }

    public function hit()
    {
        if ($this->store instanceof Redis) {
            return $this->hitRedis();
        }

        if ($this->count()) {
            $this->store->increment($this->key);
            $this->number++;
        } else {
            $this->store->set($this->key, 1, $this->time);
            $this->number = 1;
        }

        return $this;
    }

    public function clear()
    {
        $this->number = 0;

        $this->store->set($this->key, $this->number, $this->time);

        return $this;
    }

    public function count()
    {
        if ($this->number !== null) {
            return $this->number;
        }

        $this->number = (int) $this->store->get($this->key);

        if (!$this->number) {
            $this->number = 0;
        }

        return $this->number;
    }

    public function check()
    {
        return $this->count() < $this->limit;
    }

    /**
     * Get the store instance.
     *
     * @return AdapterInterface
     */
    public function getStore()
    {
        return $this->store;
    }

    protected function hitRedis()
    {
        $lua = 'local v = redis.call(\'incr\', KEYS[1]) '.
            'if v>1 then return v '.
            'else redis.call(\'setex\', KEYS[1], ARGV[1], 1) return 1 end';

        $this->number = $this->store->connection()->eval($lua, 1, $this->computeRedisKey(), $this->time);

        return $this;
    }

    /**
     * Compute the cache key for redis.
     *
     * @return string
     */
    protected function computeRedisKey()
    {
        return $this->store->getPrefix().$this->key;
    }
}