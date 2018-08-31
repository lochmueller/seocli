<?php

declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: tim
 * Date: 31.08.18
 * Time: 15:12.
 */

namespace SEOCLI\Output;

class Text implements OutputInterface
{
    /**
     * @return string
     */
    public function render(): string
    {
        return 'TXT';
    }
}
