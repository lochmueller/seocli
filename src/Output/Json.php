<?php

declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: tim
 * Date: 31.08.18
 * Time: 15:13.
 */

namespace SEOCLI\Output;

class Json implements OutputInterface
{
    /**
     * @return string
     */
    public function render(): string
    {
        return 'JSON';
    }
}
