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
     * Render JSON.
     *
     * @param array $table
     * @param array $topLists
     *
     * @return string
     */
    public function render(array $table, array $topLists = []): string
    {
        $data = [
            'all' => $table,
            'topLists' => $topLists,
        ];

        return \json_encode($data);
    }
}
