<?php

declare(strict_types=1);

namespace Sinbadxiii\PhalconThrottle;

use Sinbadxiii\PhalconThrottle\Cache\Factory;
use Sinbadxiii\PhalconThrottle\Transformers\TransformerFactory;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class ThrottleProvider implements ServiceProviderInterface
{
    /**
     * @var string
     */
    protected $providerName = 'throttle';

    /**
     * @param DiInterface $di
     */
    public function register(DiInterface $di): void
    {
        $cache          = $di->getShared('cache');

        $di->set($this->providerName, function () use ($cache) {

            $factory     = new Factory($cache);
            $transformer = new TransformerFactory();

            return new Throttle($factory, $transformer);
        });
    }
}