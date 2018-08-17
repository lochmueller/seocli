<?php

declare(strict_types = 1);

namespace SEOCLI;

trait Singleton
{
    /**
     * Instance.
     *
     * @var static
     */
    protected static $_instance = null;

    /**
     * Constructor.
     */
    protected function __construct()
    {
    }

    /**
     * Clone.
     */
    protected function __clone()
    {
    }

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
