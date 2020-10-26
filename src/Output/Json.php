<?php

/**
 * Json.
 */

declare(strict_types=1);

namespace SEOCLI\Output;

/**
 * Json.
 */
class Json implements OutputInterface
{
    /**
     * Render JSON.
     */
    public function render(array $table, array $topLists = []): string
    {
        $data = [
            'all' => $table,
            'topLists' => $topLists,
        ];

        return json_encode($data, JSON_PRETTY_PRINT);
    }
}
