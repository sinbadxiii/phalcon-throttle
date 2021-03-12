<?php

declare(strict_types=1);

namespace Sinbadxiii\PhalconThrottle\Transformers;

use Sinbadxiii\PhalconThrottle\Data;

class RequestTransformer implements TransformerInterface
{
    public function transform($data, int $limit = 5, int $time = 60)
    {
        return new Data((string) $data->getClientIp(), (string) $data->path(), (int) $limit, (int) $time);
    }
}