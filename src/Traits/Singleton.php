<?php

/**
 * Trait Singleton.
 */

declare(strict_types = 1);

namespace SEOCLI\Traits;

/**
 * Trait Singleton.
 */
trait Singleton
{
    use SingletonInstance;

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
}
