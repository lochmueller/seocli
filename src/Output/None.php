<?php

/**
 * None.
 */

declare(strict_types=1);

namespace SEOCLI\Output;

/**
 * None.
 */
class None implements OutputInterface
{
    /**
     * Render None.
     */
    public function render(array $table, array $topLists = []): string
    {
        return '';
    }
}
