<?php

/**
 * Trait Cache.
 */

declare(strict_types = 1);

namespace SEOCLI\Traits;

/**
 * Trait Cache.
 */
trait Cache
{
    /**
     * Cache.
     *
     * @var array
     */
    protected static $cache = [];

    /**
     * Get the cache entry.
     *
     * @param string   $identifier
     * @param callable $callback
     *
     * @return mixed
     */
    public function getCache(string $identifier, callable $callback)
    {
        if ($this->hasCache($identifier)) {
            return self::$cache[$identifier];
        }
        self::$cache[$identifier] = \call_user_func($callback);

        return self::$cache[$identifier];
    }

    /**
     * Check if there is a cache entry.
     *
     * @param string $identifier
     *
     * @return bool
     */
    public function hasCache(string $identifier): bool
    {
        return \array_key_exists($identifier, self::$cache);
    }
}
