<?php

declare(strict_types=1);

namespace Sinbadxiii\PhalconThrottle\Cache;

use Sinbadxiii\PhalconThrottle\Data;

interface FactoryInterface
{
    public function make(Data $data);
}