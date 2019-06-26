<?php

namespace App\Cache;

use App\Exceptions\RequestException;
use Closure;
use Psr\SimpleCache\CacheInterface;

class CachingRequester
{
    const HARD_TTL = 2 * 24 * 60 * 60;

    /** @var CacheInterface */
    private $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param string  $cacheKey
     * @param int     $ttl
     * @param Closure $requestCaller
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws RequestException
     *
     * @return mixed
     */
    public function load($cacheKey, $ttl, $requestCaller)
    {
        /** @var CacheEntity $entity */
        $entity = $this->cache->get($cacheKey);

        if ($entity === null) {
            return $this->fetchAndCache($cacheKey, $requestCaller);
        }

        if ($entity->olderThan($ttl)) {
            try {
                return $this->fetchAndCache($cacheKey, $requestCaller);
            } catch (RequestException $e) {
                return $entity->value;
            }
        }

        return $entity->value;
    }

    /**
     * @param string   $cacheKey
     * @param callable $requestCaller
     *
     * @throws RequestException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *
     * @return mixed
     */
    protected function fetchAndCache($cacheKey, $requestCaller)
    {
        $response = $this->fetch($requestCaller);
        $this->cache->set($cacheKey, $response, static::HARD_TTL);

        return $response;
    }

    /**
     * @param callable $requestCaller
     *
     * @throws RequestException
     *
     * @return mixed
     */
    protected function fetch($requestCaller)
    {
        if (!getenv('LICENSE')) {
            return;
        }

        $response = call_user_func($requestCaller);

        if ($response === null && getenv('LICENSE') !== 'false') {
            throw new RequestException('Could not connect to the license server.');
        }

        return $response;
    }
}
