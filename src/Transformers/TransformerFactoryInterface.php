<?php

declare(strict_types=1);

namespace Sinbadxiii\PhalconThrottle\Transformers;

interface TransformerFactoryInterface
{
    public function make($data);
}