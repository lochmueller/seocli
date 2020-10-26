<?php

/**
 * Format.
 */

declare(strict_types=1);

namespace SEOCLI;

/**
 * Format.
 */
class Format
{
    /**
     * Format mega bytes.
     *
     * @param float|int $bytes
     */
    public function megaBytes($bytes): string
    {
        return round(((float) $bytes) / 1024 / 1024, 2).' MB';
    }
}
