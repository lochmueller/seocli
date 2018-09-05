<?php

/**
 * Interface PaserInterface.
 */

declare(strict_types = 1);

namespace SEOCLI\Parser;

/**
 * Interface PaserInterface.
 */
interface PaserInterface
{
    /**
     * Parse.
     *
     * @param \SEOCLI\Uri $uri
     * @param string      $content
     *
     * @return array
     */
    public function parse(\SEOCLI\Uri $uri, string $content): array;
}
