<?php

declare(strict_types=1);

namespace Sinbadxiii\PhalconThrottle;

use Sinbadxiii\PhalconThrottle\Cache\FactoryInterface;
use Sinbadxiii\PhalconThrottle\Transformers\TransformerFactoryInterface;

/**
 * Class Throttle
 * @package Sinbadxiii\PhalconThrottle
 */
class Throttle
{
    protected $throttlers = [];
    protected $factory;
    protected $transformer;

    public function __construct(FactoryInterface $factory, TransformerFactoryInterface $transformer)
    {
        $this->factory = $factory;
        $this->transformer = $transformer;
    }

    public function get($data, int $limit = 5, int $time = 60)
    {
        $transformed = $this->transformer->make($data)->transform($data, $limit, $time);

        if (!array_key_exists($key = $transformed->getKey(), $this->throttlers)) {
            $this->throttlers[$key] = $this->factory->make($transformed);
        }

        return $this->throttlers[$key];
    }

    public function getFactory()
    {
        return $this->factory;
    }

    public function getTransformer()
    {
        return $this->transformer;
    }

    public function __call(string $method, array $params)
    {
        return $this->get(...$params)->$method();
    }
}