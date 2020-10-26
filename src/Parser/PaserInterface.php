<?php

/**
 * Interface PaserInterface.
 */

declare(strict_types=1);

namespace SEOCLI\Parser;

/**
 * Interface PaserInterface.
 */
interface PaserInterface
{
    /**
     * Parse.
     */
    public function parse(\SEOCLI\Uri $uri, string $content): array;
}
