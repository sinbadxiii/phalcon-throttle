<?php

declare(strict_types=1);

namespace Sinbadxiii\PhalconThrottle\Transformers;

interface TransformerInterface
{
    public function transform($data, int $limit = 5, int $time = 60);
}