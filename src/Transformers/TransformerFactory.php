<?php

declare(strict_types=1);

namespace Sinbadxiii\PhalconThrottle\Transformers;

use InvalidArgumentException;
use Phalcon\Http\Request;

class TransformerFactory implements TransformerFactoryInterface
{
    public function make($data)
    {
        if (is_object($data) && $data instanceof Request) {
            return new RequestTransformer();
        }

        if (is_array($data)) {
            return new ArrayTransformer();
        }

        throw new InvalidArgumentException(sprintf('An array, or an instance of %s was expected.', Request::class));
    }
}