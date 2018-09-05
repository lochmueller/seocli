<?php

/**
 * None.
 */

declare(strict_types = 1);

namespace SEOCLI\Output;

/**
 * None.
 */
class None implements OutputInterface
{
    /**
     * Render None.
     *
     * @param array $table
     * @param array $topLists
     *
     * @return string
     */
    public function render(array $table, array $topLists = []): string
    {
        return '';
    }
}
