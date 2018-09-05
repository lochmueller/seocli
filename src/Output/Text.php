<?php

/**
 * Text.
 */

declare(strict_types = 1);

namespace SEOCLI\Output;

use SEOCLI\Cli;

/**
 * Text.
 */
class Text implements OutputInterface
{
    /**
     * Render Text.
     *
     * @param array $table
     * @param array $topLists
     *
     * @return string
     */
    public function render(array $table, array $topLists = []): string
    {
        $cli = Cli::getInstance();

        $cli->blue('All result ' . \count($table) . ':');
        $cli->table($table);

        foreach ($topLists as $label => $innerTable) {
            $cli->red('Top ' . \count($innerTable) . ': ' . $label);
            $cli->table($innerTable);
        }

        return '';
    }
}
