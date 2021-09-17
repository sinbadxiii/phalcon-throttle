<?php

declare(strict_types=1);

namespace Sinbadxiii\PhalconThrottle\Throttlers;

interface ThrottlerInterface
{
    public function attempt();

    public function hit();

    public function clear();

    public function count();

    public function check();
}