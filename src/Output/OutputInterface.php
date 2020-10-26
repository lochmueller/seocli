<?php

/**
 * Interface OutputInterface.
 */

declare(strict_types=1);

namespace SEOCLI\Output;

/**
 * Interface OutputInterface.
 */
interface OutputInterface
{
    /**
     * Render.
     */
    public function render(array $table, array $topLists = []): string;
}
