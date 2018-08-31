<?php

declare(strict_types = 1);

namespace SEOCLI\Traits;

/**
 * Trait SingletonInstance.
 */
trait SingletonInstance
{
    /**
     * Instance.
     *
     * @var static
     */
    protected static $_instance = null;

    /**
     * Get instance.
     *
     * @return static
     */
    public static function getInstance()
    {
        if (null === static::$_instance) {
            static::$_instance = new static();
        }

        return static::$_instance;
    }
}
