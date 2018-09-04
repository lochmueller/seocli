<?php

declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: tim
 * Date: 31.08.18
 * Time: 15:13.
 */

namespace SEOCLI\Output;

interface OutputInterface
{
    /**
     * @param array $table
     * @param array $topLists
     *
     * @return string
     */
    public function render(array $table, array $topLists = []): string;
}
