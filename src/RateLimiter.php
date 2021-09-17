<?php

declare(strict_types=1);

namespace Sinbadxiii\PhalconThrottle;

use DateInterval;
use DateTimeInterface;

class RateLimiter
{
    protected $cache;

    protected $limiters = [];

    public function __construct($cache)
    {
        $this->cache = $cache;
    }

    public function for(string $name, $callback)
    {
        $this->limiters[$name] = $callback;

        return $this;
    }

    public function limiter(string $name)
    {
        return $this->limiters[$name] ?? null;
    }

    public function tooManyAttempts($key, $maxAttempts)
    {
        if ($this->attempts($key) >= $maxAttempts) {

            if ($this->cache->has($key . ':timer')) {
                return true;
            }

            $this->resetAttempts($key);
        }

        return false;
    }

    public function hit($key, $decaySeconds = 60)
    {
        $this->cache->set(
            $key . ':timer', $this->availableAt($decaySeconds), $decaySeconds
        );

        $hits = $this->cache->increment($key);
        $this->cache->set($key, $hits, $decaySeconds);

        return $hits;
    }

    public function attempts($key)
    {
        return $this->cache->get($key, 0);
    }

    public function resetAttempts($key)
    {
        return $this->cache->delete($key);
    }

    public function retriesLeft($key, $maxAttempts)
    {
        $attempts = $this->attempts($key);

        return $maxAttempts - $attempts;
    }

    public function clear($key)
    {
        $this->resetAttempts($key);

        $this->cache->delete($key.':timer');
    }

    public function availableIn($key)
    {
        return $this->cache->get($key.':timer') - $this->currentTime();
    }

    protected function secondsUntil($delay)
    {
        $delay = $this->parseDateInterval($delay);

        return $delay instanceof DateTimeInterface
            ? max(0, $delay->getTimestamp() - $this->currentTime())
            : (int) $delay;
    }

    protected function availableAt($delay = 0)
    {

        $delay = $this->parseDateInterval($delay);

        return $delay instanceof DateTimeInterface
            ? $delay->getTimestamp()
            : date("U") + $delay;
    }

    protected function parseDateInterval($delay)
    {
        if ($delay instanceof DateInterval) {
            $delay +=  date("U");
        }

        return $delay;
    }

    protected function currentTime()
    {
        return date("U");
    }
}