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
     * @param \SEOCLI\Uri $uri
     * @param string      $content
     *
     * @return mixed
     */
    public function parse(\SEOCLI\Uri $uri, string $content): array;
}
