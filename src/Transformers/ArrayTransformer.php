<?php

declare(strict_types=1);

namespace Sinbadxiii\PhalconThrottle\Transformers;

use Sinbadxiii\PhalconThrottle\Data;
use InvalidArgumentException;
use Phalcon\Helper\Arr;

class ArrayTransformer implements TransformerInterface
{
    public function transform($data, int $limit = 10, int $time = 60)
    {
        if (($ip = Arr::get($data, 'ip')) && ($route = Arr::get($data, 'route'))) {
            return new Data((string) $ip, (string) $route, (int) $limit, (int) $time);
        }

        throw new InvalidArgumentException('The data array does not provide the required ip and route information.');
    }
}