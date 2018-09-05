<?php

/**
 * Interface OutputInterface.
 */

declare(strict_types = 1);

namespace SEOCLI\Output;

/**
 * Interface OutputInterface.
 */
interface OutputInterface
{
    /**
     * Render.
     *
     * @param array $table
     * @param array $topLists
     *
     * @return string
     */
    public function render(array $table, array $topLists = []): string;
}
