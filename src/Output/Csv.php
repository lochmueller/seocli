<?php

/**
 * Csv.
 */

declare(strict_types = 1);

namespace SEOCLI\Output;

/**
 * Csv.
 */
class Csv implements OutputInterface
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
        $allTable = [
            ['All fields'],
        ];
        $allTable = \array_merge($allTable, $table);

        foreach ($topLists as $label => $innerTable) {
            $allTable = \array_merge($allTable, [[$label]]);
            $allTable = \array_merge($allTable, $innerTable);
        }

        // php://memory
        $fp = \fopen('php://output', 'w');
        foreach ($allTable as $fields) {
            \fputcsv($fp, $fields);
        }
        \fclose($fp);

        return '';
    }
}
