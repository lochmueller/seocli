<?php

/**
 * Json.
 */

declare(strict_types = 1);

namespace SEOCLI\Output;

/**
 * Json.
 */
class Json implements OutputInterface
{
    /**
     * @param array $table
     * @param array $topLists
     *
     * @return string
     */
    public function render(array $table, array $topLists = []): string
    {
        return 'JSON';
    }
}
